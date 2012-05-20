<?php

namespace Behat\Mink\Driver\Goutte;

use Goutte\Client as BaseClient;
use Guzzle\Message\Response as GuzzleResponse;

/*
 * This file is part of the Behat\Mink.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Client overrides to support Mink functionality.
 */
class Client extends BaseClient
{
    /**
     * Reads response meta tags to guess content-type charset.
     */
    protected function createResponse(GuzzleResponse $response)
    {
        $body        = $response->getBody(true);
        $statusCode  = $response->getStatusCode();
        $headers     = $response->getHeaders()->getAll();
        $contentType = $response->getContentType();

        if (!$contentType || false === strpos($contentType, 'charset=')) {
            if (preg_match('/\<meta[^\>]+charset *= *["\']?([a-zA-Z\-0-9]+)/', $body, $matches)) {
                $contentType .= ';charset='.$matches[1];
            }
        }
        $headers['Content-Type'] = $contentType;

        return new Response($body, $statusCode, $headers);
    }
}
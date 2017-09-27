<?php

namespace benliev\Middleware;

use GuzzleHttp\Psr7\Response;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Middleware to remove the trailing slash.
 * @author Lievens Benjamin <l.benjamin185@gmail.com>
 * @package benliev\Middleware
 */
class TrailingSlashMiddleware implements MiddlewareInterface
{

    /**
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        $uri = (string)$request->getUri();
        if (!empty($uri) && $uri[-1] == '/') {
            return new Response(301, ['Location' => substr($uri, 0, -1)]);
        }
        return $delegate->process($request);
    }
}

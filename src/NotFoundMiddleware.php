<?php

namespace benliev\Middleware;

use GuzzleHttp\Psr7\Response;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Middleware to return a 404 response with Error 404 body.
 * This middleware must be at the end of the dispatcher.
 * @author Lievens Benjamin <l.benjamin185@gmail.com>
 * @package benliev\Middleware
 */
class NotFoundMiddleware implements MiddlewareInterface
{
    /**
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        return new Response(404, [], 'Error 404');
    }
}

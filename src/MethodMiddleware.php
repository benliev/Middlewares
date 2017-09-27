<?php

namespace benliev\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Middleware to override the request method using parameter _method provided in the request body.
 * @author Lievens Benjamin <l.benjamin185@gmail.com>
 * @package benliev\Middleware
 */
class MethodMiddleware implements MiddlewareInterface
{
    /**
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $parsedBody = $request->getParsedBody();
        $method = $parsedBody['_method'] ?? false;
        if ($method && in_array($method, ['DELETE', 'PUT'])) {
            $request = $request->withMethod($method);
        }
        return $delegate->process($request);
    }
}

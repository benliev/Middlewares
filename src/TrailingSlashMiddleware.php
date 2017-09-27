<?php

namespace benliev\Middleware;

use GuzzleHttp\Psr7\Response;
use Interop\Http\Factory\ResponseFactoryInterface;
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
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * NotFoundMiddleware constructor.
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        $uri = (string)$request->getUri();
        if (!empty($uri) && $uri[-1] == '/') {
            return $this->responseFactory->createResponse(301)
                ->withHeader('Location', substr($uri, 0, -1));
        }
        return $delegate->process($request);
    }
}

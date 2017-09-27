<?php

namespace benliev\Middleware;

use GuzzleHttp\Psr7\Response;
use Interop\Http\Factory\ResponseFactoryInterface;
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
        $response = $this->responseFactory->createResponse(404);
        $response->getBody()->write('Error 404');
        return $response;
    }
}

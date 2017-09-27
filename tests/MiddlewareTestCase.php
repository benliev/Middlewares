<?php

namespace benliev\Middleware\Tests;

use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class MiddlewareTestCase
 * @author Lievens Benjamin <l.benjamin185@gmail.com>
 * @package benliev\Middleware\Tests
 */
class MiddlewareTestCase extends TestCase
{

    protected function makeRequest(string $method = 'GET', $uri = '', ?array $params = null)
    {
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method('getMethod')->willReturn($method);
        $request->method('getParsedBody')->willReturn($params);
        $request->method('getUri')->willReturn($uri);
        return $request;
    }

    protected function makeDelegate()
    {
        $delegate = $this->getMockBuilder(DelegateInterface::class)->getMock();
        $delegate->method('process')->willReturn($this->makeResponse());
        return $delegate;
    }

    protected function makeResponse()
    {
        return $this->getMockBuilder(ResponseInterface::class)->getMock();
    }

}
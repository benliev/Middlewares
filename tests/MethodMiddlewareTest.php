<?php

namespace benliev\Middleware\Tests;

use benliev\Middleware\MethodMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class MethodMiddlewareTest
 * @author Lievens Benjamin <l.benjamin185@gmail.com>
 * @package benliev\Middleware\Tests
 */
class MethodMiddlewareTest extends TestCase
{

    /** @var MethodMiddleware */
    private $middleware;

    private function makeDelegate()
    {
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $delegate = $this->getMockBuilder(DelegateInterface::class)->getMock();
        $delegate->method('process')->willReturn($response);
        return $delegate;
    }

    public function setUp()
    {
        $this->middleware = new MethodMiddleware();
    }

    public function testAddMethod()
    {
        $delegate = $this->makeDelegate();
        ;
        $delegate->expects($this->once())
            ->method('process')
            ->with($this->callback(function (ServerRequestInterface $request) {
                return $request->getMethod() == 'DELETE';
            }))
        ;
        $request = (new ServerRequest('POST', '/demo'))->withParsedBody(['_method' => 'DELETE']);
        $this->middleware->process($request, $delegate);
    }
}

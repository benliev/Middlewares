<?php

namespace benliev\Middleware\Tests;

use benliev\Middleware\MethodMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class MethodMiddlewareTest
 * @author Lievens Benjamin <l.benjamin185@gmail.com>
 * @package benliev\Middleware\Tests
 */
class MethodMiddlewareTest extends MiddlewareTestCase
{

    protected function makeMiddleware()
    {
        return new MethodMiddleware();
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
        $this->makeMiddleware()->process($request, $delegate);
    }
}

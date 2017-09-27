<?php

namespace benliev\Middleware\Tests;

use benliev\Middleware\TrailingSlashMiddleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Http\Factory\Guzzle\ResponseFactory;
use Interop\Http\ServerMiddleware\DelegateInterface;

/**
 * Class TrailingSlashMiddlewareTest
 * @author Lievens Benjamin <l.benjamin185@gmail.com>
 * @package benliev\Middleware\Tests
 */
class TrailingSlashMiddlewareTest extends MiddlewareTestCase
{

    private function makeMiddleware()
    {
        return new TrailingSlashMiddleware(new ResponseFactory());
    }

    public function testRedirect()
    {
        $delegate = $this->getMockBuilder(DelegateInterface::class)->getMock();
        $request = $this->makeRequest('GET', '/demo/');
        $response = $this->makeMiddleware()->process($request, $delegate);
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertContains('/demo', $response->getHeader('Location'));
    }

    public function testNotRedirect()
    {
        $delegate = $this->getMockBuilder(DelegateInterface::class)
            ->setMethods(['process'])
            ->getMock();

        $delegate->expects($this->once())
            ->method('process')
            ->willReturn(new Response());

        $request = new ServerRequest('GET', '/demo');
        $this->makeMiddleware()->process($request, $delegate);
    }
}

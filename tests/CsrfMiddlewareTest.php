<?php

namespace benliev\Middleware\Tests;

use benliev\Middleware\CsrfMiddleware;
use benliev\Middleware\Exceptions\InvalidCsrfException;
use benliev\Middleware\Exceptions\NoCsrfException;

/**
 * Class CSRFMiddlewareTest
 * @author Lievens Benjamin <l.benjamin185@gmail.com>
 * @package benliev\Middleware\Tests
 */
class CsrfMiddlewareTest extends MiddlewareTestCase
{
    private function makeMiddleware(&$session = []) : CsrfMiddleware
    {
        return new CsrfMiddleware($session);
    }

    public function testAcceptValidSession()
    {
        $a = [];
        $b = $this->getMockBuilder(\ArrayAccess::class)->getMock();
        $middleware_a = $this->makeMiddleware($a);
        $middleware_b = $this->makeMiddleware($b);
        $this->assertInstanceOf(CsrfMiddleware::class, $middleware_a);
        $this->assertInstanceOf(CsrfMiddleware::class, $middleware_b);
    }

    public function testRejectInvalidSession()
    {
        $a = new \stdClass();
        $this->expectException(\TypeError::class);
        $this->makeMiddleware($a);
    }

    public function testGetPass()
    {
        $middleware = $this->makeMiddleware();
        $delegate = $this->makeDelegate();
        $delegate->expects($this->once())->method('process');
        $middleware->process(
            $this->makeRequest('GET'),
            $delegate
        );
    }

    public function testPreventPost()
    {
        $middleware = $this->makeMiddleware();
        $delegate = $this->makeDelegate();
        $delegate->expects($this->never())->method('process');
        $this->expectException(NoCsrfException::class);
        $middleware->process(
            $this->makeRequest('POST'),
            $delegate
        );
    }

    public function testPostWithValidToken()
    {
        $middleware = $this->makeMiddleware();
        $token = $middleware->generateToken();
        $delegate = $this->makeDelegate();
        $delegate->expects($this->once())->method('process')->willReturn($this->makeResponse());
        $middleware->process(
            $this->makeRequest('POST', '/demo', ['_csrf' => $token]),
            $delegate
        );
    }

    public function testPostWithInvalidToken()
    {
        $middleware = $this->makeMiddleware();
        $token = $middleware->generateToken();
        $delegate = $this->makeDelegate();
        $delegate->expects($this->never())->method('process');
        $this->expectException(InvalidCsrfException::class);
        $middleware->process(
            $this->makeRequest('POST', '/demo', ['_csrf' => '']),
            $delegate
        );
    }

    public function testPostWithDoubleToken()
    {
        $middleware = $this->makeMiddleware();
        $token = $middleware->generateToken();
        $delegate = $this->makeDelegate();
        $delegate->expects($this->once())->method('process')->willReturn($this->makeResponse());
        $middleware->process(
            $this->makeRequest('POST', '/demo', ['_csrf' => $token]),
            $delegate
        );
        $this->expectException(InvalidCsrfException::class);
        $middleware->process(
            $this->makeRequest('POST', '/demo', ['_csrf' => $token]),
            $delegate
        );
    }

    public function testLimitToken()
    {
        $session = [];
        $middleware = $this->makeMiddleware($session);
        $token = null;
        for ($i =0; $i < 100; $i++) {
            $token = $middleware->generateToken();
        }
        $this->assertCount(50, $session[$middleware->getSessionKey()]);
        $this->assertEquals($token, $session[$middleware->getSessionKey()][49]);
    }
}

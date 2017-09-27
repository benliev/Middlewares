<?php

namespace benliev\Middleware\Tests;

use benliev\Middleware\NotFoundMiddleware;
use Http\Factory\Guzzle\ResponseFactory;

/**
 * Class NotFoundMiddlewareTest
 * @author Lievens Benjamin <l.benjamin185@gmail.com>
 * @package benliev\Middleware\Tests
 */
class NotFoundMiddlewareTest extends MiddlewareTestCase
{

    private function makeMiddleware()
    {
        return new NotFoundMiddleware(new ResponseFactory());
    }

    public function testProcess()
    {
        $response = $this->makeMiddleware()
            ->process($this->makeRequest(), $this->makeDelegate());
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Error 404', (string)$response->getBody());
    }

}
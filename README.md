# PHP middleware

[![Build Status](https://travis-ci.org/benliev/Middlewares.svg?branch=master)](https://travis-ci.org/benliev/Middlewares) [![Coverage Status](https://coveralls.io/repos/github/benliev/Middlewares/badge.svg?branch=master)](https://coveralls.io/github/benliev/Middlewares?branch=master)

Collection of [PSR-15 Middleware](https://github.com/php-fig/fig-standards/blob/master/proposed/http-middleware/middleware.md).

## Requirements

PHP >= 7.1

## Installation

```shell
composer require benliev/middleware
```

### Test

For test this library :

```shell
composer test
```

## Usage example

```php
$responseFactory = new \Http\Factory\Guzzle\ResponseFactory();
$request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
$response = (new Dispatcher())
    ->pipe(new TrailingSlashMiddleware($responseFactory))
    ->pipe(new MethodMiddleware())
    ->pipe(new CsrfMiddleware())
    ->pipe(new NotFoundMiddleware($responseFactory))
    ->process($request));
\Http\Response\send($response);
```

The dispatcher has the role of storing the middleware and then of executing them.

## Available middlewares

* TrailingSlashMiddleware : Middleware to remove the trailing slash.
* MethodMiddleware : Middleware to override the request method using parameter _method provided in the request body.
* CsrfMiddleware : Middleware for CSRF protection.
* NotFoundMiddleware :  Middleware to return a 404 response with Error 404 body. This middleware must be at the end of the dispatcher.

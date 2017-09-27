# PHP middleware

Collection of [PSR-15 Middleware](https://github.com/php-fig/fig-standards/blob/master/proposed/http-middleware/middleware.md).

## Requirements

PHP >= 7.1

## Installation

For the moment this library is not on packagist.

```shell
git clone blabla
composer install
```

### Test

For test this library :

```shell
composer test
```

## Usage example

```php
$request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals()::fromGlobals();
$response = (new Dispatcher())
    ->pipe(TrailingSlashMiddleware::class)
    ->pipe(MethodMiddleware::class)
    ->pipe(CsrfMiddleware::class)
    ->pipe(NotFoundMiddleware::class)
    ->process($request));
\Http\Response\send($response);
```

The dispatcher has the role of storing the middleware and then of executing them.

## Available middlewares

* TrailingSlashMiddleware : Middleware to remove the trailing slash.
* MethodMiddleware : Middleware to override the request method using parameter _method provided in the request body.
* CsrfMiddleware : Middleware for CSRF protection.
* NotFoundMiddleware :  Middleware to return a 404 response with Error 404 body. This middleware must be at the end of the dispatcher.

# middleware

### Basics

Middleware provide a convenient mechanism for filtering requests entering your application. For example, you could have a middleware that checks if the connection has been authenticated, and rejects the request if the user is not authenticated. Middlewares in sockets behave very similiar to [laravel's middleware](https://laravel.com/docs/middleware) mechanism.

### Creating middlewares

Writing middleware for sockets is very easy, you just implement the `\Experus\Sockets\Contracts\Middlewares\Middleware` contract:
```php
<?php

namespace App\Sockets\Middlewares;

use Closure;
use Experus\Sockets\Core\Server\SocketRequest;
use Experus\Sockets\Contracts\Middlewares\Middleware;

/**
 * Class DummyMiddleware
 * @package App\Sockets\Middlewares
 */
class DummyMiddleware implements Middleware
{
    /**
     * Pass the request through the middleware.
     *
     * @param mixed $request The request to handle.
     * @param Closure $next The next middleware.
     * @return array|null|object A premature response or nothing.
     */
    public function handle(SocketRequest &$request, Closure $next)
    {
        // TODO: Implement handle() method.
        return $next($request);
    }
}
```
Middlewares are resolved from the container, meaning that you can specify any dependency you might need in your constructor and Laravel will take care of them for you.

### Accepting or rejecting a request

Say you are writing a middleware that blocks users that are not authenticated. If a user is authenticated you want the request to pass through and be handled, and if the user is not authenticated you want to block the request and close the connection. Let's set up an example, we'll explain it more in depth in a second:
```php
<?php

namespace App\Sockets\Middlewares;

use Closure;
use Experus\Sockets\Core\Server\SocketRequest;
use Experus\Sockets\Contracts\Middlewares\Middleware;

/**
 * Class AuthenticatedMiddleware
 * @package App\Sockets\Middlewares
 */
class AuthenticatedMiddleware implements Middleware
{
    /**
     * Pass the request through the middleware.
     *
     * @param mixed $request The request to handle.
     * @param Closure $next The next middleware.
     * @return array|null|object A premature response or nothing.
     */
    public function handle(SocketRequest &$request, Closure $next)
    {
        $authenticated = true; // check if your user is authenticated

        if ( ! $authenticated) {
            throw new UnauthenticatedException();
        }

        return $next($request);
    }
}
```

In this example, if the user is not authenticated we'll throw an exception. We could close the connection in the exception handler, or just tell the user about it (see [handling exceptions](exceptions.md) for more info). If the user is authenticated, we'll return `$next($request)` which passes the request to the next middleware in the stack.

In short, you can *accept* a request by returning `$next($request)` or *reject* is by throwing an exception.

### Skipping middlewares (**NOT** recommended)

If you return `null` in your middleware, you will skip all the remaining middlewares in the stack, this is generally **not** a good idea, as it could cause errors that are **very** hard to debug. However, it is documented here in case you ever find a valid use case for it.

### Global middleware vs route middleware

Sockets (just like laravel) has two sorts of middlewares: *global* and *route* middlewares. The difference is very simple, a *global* middleware will be ran on every incoming request while a *route* middleware will only run on the routes it's defined to run on.

You can register *global* middlewares in your [provider](provider.md) and route middlewares are registered in the [routing stack](routing.md).

### Named middlewares

**This feature is not implemented yet**

### Generating middleware

Since writing middleware boilerplate code is tedious and not exactly fun, sockets provides you with a command that generates the boilerplate code for you.

Simply run `php artisan socket:middleware SocketMiddleware` and sockets will generate a middleware named *SocketMiddleware* for you. You can obviously specify any name you want in place of *SocketMiddleware*. For a reference of possible parameters and available command see the [documentation](artisan.md).

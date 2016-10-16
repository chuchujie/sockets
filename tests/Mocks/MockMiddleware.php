<?php
// Created by dealloc. All rights reserved.

namespace Mocks;

use Closure;
use Experus\Sockets\Contracts\Middlewares\Middleware;
use Experus\Sockets\Core\Server\SocketRequest;

/**
 * Class MockMiddleware is a mocked middleware class.
 * @package Mocks
 */
class MockMiddleware implements Middleware
{
    /**
     * Pass the request through the middleware.
     *
     * @param SocketRequest &$request Reference to the request to handle.
     * @param Closure $next The next middleware.
     * @return array|null|object A premature response or nothing.
     */
    public function handle(SocketRequest &$request, Closure $next)
    {
        return $next($request);
    }
}
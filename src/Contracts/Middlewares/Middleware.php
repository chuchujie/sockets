<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Contracts\Middlewares;

use Closure;
use Experus\Sockets\Core\Server\SocketRequest;

/**
 * Interface Middleware provides a contract for generic middleware handling.
 * @package Experus\Sockets\Contracts\Middlewares
 */
interface Middleware
{
    /**
     * Pass the request through the middleware.
     *
     * @param SocketRequest &$request Reference to the request to handle.
     * @param Closure $next The next middleware.
     * @return array|null|object A premature response or nothing.
     */
    public function handle(SocketRequest &$request, Closure $next);
}
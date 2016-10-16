<?php
// Created by dealloc. All rights reserved.

namespace Mocks;

use Closure;
use Experus\Sockets\Contracts\Middlewares\Middleware;
use Experus\Sockets\Core\Server\SocketRequest;

/**
 * Class BlockMiddleware is a mock middleware that returns rather than passing through.
 * @package Mocks
 */
class BlockMiddleware implements Middleware
{
    const RESPONSE_TEXT = 'dummy';

    /**
     * Pass the request through the middleware.
     *
     * @param SocketRequest &$request Reference to the request to handle.
     * @param Closure $next The next middleware.
     * @return array|null|object A premature response or nothing.
     */
    public function handle(SocketRequest &$request, Closure $next)
    {
        return self::RESPONSE_TEXT;
    }
}
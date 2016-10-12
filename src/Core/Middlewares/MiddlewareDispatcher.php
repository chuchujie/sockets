<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Middlewares;

use Experus\Sockets\Core\Server\SocketRequest;

/**
 * Class Dispatcher provides logic for running a request through a middleware stack.
 * @package Experus\Sockets\Core\Middlewares
 */
trait MiddlewareDispatcher
{
    /**
     * Run the request through the provided middlewares.
     *
     * @param array $middlewares
     * @param SocketRequest $request
     * @return array|object|null
     */
    public function runThrough(array $middlewares, SocketRequest $request)
    {
        if (empty($middlewares)) {
            return null;
        }

        $size = sizeof($middlewares);
        $index = 0;

        $next = function(&$request) use ($size, &$index, &$middlewares, &$next) {
            if ($size <= $index) {
                return null;
            }

            return $middlewares[$index++]->handle($request, $next);
        };

        return $next($request);
    }
}
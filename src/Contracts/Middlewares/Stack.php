<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Contracts\Middlewares;

use Experus\Sockets\Exceptions\MiddlewareNotFoundException;

/**
 * Interface Stack is a contract for providing a middleware stack to the sockets runtime.
 * @package Experus\Sockets\Contracts\Middlewares
 */
interface Stack
{
    /**
     * Resolve a middleware from the middleware stack.
     *
     * @param $middleware
     * @return array|Middleware returns the resolved middleware or an array if the middleware was a group.
     * @throws MiddlewareNotFoundException
     */
    public function resolve($middleware);

    /**
     * Register a (named) middleware in the stack.
     *
     * @param string|array $name The name or the middleware(s) to register.
     * @param string|array|null $middleware The middleware(s) if a name was given.
     */
    public function register($name, $middleware = null);
}
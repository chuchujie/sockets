<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Middlewares;

use Experus\Sockets\Contracts\Middlewares\Middleware;
use Experus\Sockets\Contracts\Middlewares\Stack;
use Experus\Sockets\Exceptions\MiddlewareNotFoundException;

/**
 * Class SocketMiddlewareStack
 * @package Experus\Sockets\Core\Middlewares
 */
class SocketMiddlewareStack implements Stack
{
    /**
     * Resolve a middleware from the middleware stack.
     *
     * @param $middleware
     * @return array|Middleware returns the resolved middleware or an array if the middleware was a group.
     * @throws MiddlewareNotFoundException
     */
    public function resolve($middleware)
    {
        // TODO: Implement resolve() method.
    }

    /**
     * Register a (named) middleware in the stack.
     *
     * @param string|array $name The name or the middleware(s) to register.
     * @param string|array|null $middleware The middleware(s) if a name was given.
     */
    public function register($name, $middleware = null)
    {
        // TODO: Implement register() method.
    }
}
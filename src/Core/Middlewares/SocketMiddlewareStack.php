<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Middlewares;

use Experus\Sockets\Contracts\Middlewares\Middleware;
use Experus\Sockets\Contracts\Middlewares\Stack;
use Experus\Sockets\Exceptions\MiddlewareNotFoundException;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class SocketMiddlewareStack
 * @package Experus\Sockets\Core\Middlewares
 */
class SocketMiddlewareStack implements Stack
{
    /**
     * The laravel application instance.
     *
     * @var Application
     */
    private $app;

    /**
     * The global middleware stack.
     *
     * @var array
     */
    protected $global = [];

    /**
     * All aliases for middlewares.
     *
     * @var array
     */
    protected $named = [];

    /**
     * All middleware groups.
     *
     * @var array
     */
    protected $groups = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->groups[self::GLOBAL_STACK] = &$this->global; // reference the global stack as a regular group.
    }

    /**
     * Resolve a middleware from the middleware stack.
     *
     * @param string $middleware
     * @return array|Middleware returns the resolved middleware or an array if the middleware was a group.
     * @throws MiddlewareNotFoundException
     */
    public function resolve($middleware)
    {
        if (str_contains($middleware, '\\')) {
            return $this->compile($middleware);
        }

        if (array_key_exists($middleware, $this->named)) {
            return $this->compile($this->named[$middleware]);
        }

        if (array_key_exists($middleware, $this->groups)) {
            return $this->compile($this->groups[$middleware]);
        }

        throw new MiddlewareNotFoundException($middleware);
    }

    /**
     * Register a (named) middleware in the stack.
     * If a name and middleware is given, it will register an alias.
     * If only a middleware is given, it will be registered in the global middleware stack.
     * If a name and an array is given, the middlewares will be registered as a group.
     *
     * @param string|array $name The name or the middleware(s) to register.
     * @param string|array|null $middleware The middleware(s) if a name was given.
     */
    public function register($name, $middleware = null)
    {
        if (is_null($middleware)) {
            $this->global[] = $name;
        } else if (is_array($middleware)) {
            $this->groups[$name] = $middleware;
        } else {
            $this->named[$name] =$middleware;
        }
    }

    /**
     * Compile middleware(s) if needed.
     *
     * @param $middleware
     * @return array|Middleware
     */
    private function compile($middleware)
    {
        if (is_array($middleware)) {
            foreach ($middleware as &$class) {
                $class = $this->resolve($class);
            }

            return $middleware;
        }

        return $this->app->make($middleware);
    }
}
<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Middlewares;

use Experus\Sockets\Core\Server\SocketRequest;

/**
 * Class Pipeline presents a stream like pipeline to run a request through middleware.
 * @package Experus\Sockets\Core\Middlewares
 */
class Pipeline
{
    /**
     * The middleware stack of this pipeline.
     *
     * @var array
     */
    protected $stack = [];

    /**
     * Set the middleware to stream through.
     *
     * @param array $middleware
     * @return Pipeline
     */
    public function through(array $middleware)
    {
        $this->stack = $middleware;

        return $this;
    }

    /**
     * Run the request through the pipeline.
     *
     * @param SocketRequest $request
     * @return array|null|object
     */
    public function run(SocketRequest $request)
    {
        if (empty($this->stack)) {
            return null;
        }

        $size = sizeof($this->stack);
        $index = 0;

        $next = function(&$request) use ($size, &$index, &$next) {
            if ($size <= $index) {
                return null;
            }

            return $this->stack[$index++]->handle($request, $next);
        };

        return $next($request);
    }
}
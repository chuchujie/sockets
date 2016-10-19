<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Exceptions;

use Exception;

/**
 * Class MiddlewareNotFoundException thrown when the Stack couldn't resolve the requested middleware.
 * @package Experus\Sockets\Exceptions
 */
class MiddlewareNotFoundException extends Exception
{
    /**
     * @var string
     */
    private $middleware;

    /**
     * MiddlewareNotFoundException constructor.
     * @param string $middleware
     */
    public function __construct($middleware)
    {
        parent::__construct('Could not resolve "' . $middleware . '" as a valid middleware, alias or group.');
        $this->middleware = $middleware;
    }

    /**
     * Get the middleware that failed to resolve.
     *
     * @return string
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }
}
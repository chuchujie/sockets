<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Routing;

use Closure;
use Experus\Sockets\Contracts\Routing\Router;

class SocketRouter implements Router
{
    /**
     * Register the uri in the router.
     *
     * @param string $uri
     * @param string|array|Closure $action The action to perform when the route is called.
     * @return Router
     */
    public function socket($uri, $action)
    {
        // TODO: Implement socket() method.
    }

    /**
     * Group a set of routes together.
     *
     * @param array $attributes The attributes to set on each route in the group.
     * @param Closure $callback
     * @return Router
     */
    public function group(array $attributes, Closure $callback)
    {
        // TODO: Implement group() method.
    }
}
<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Routing;

use Closure;
use Experus\Sockets\Contracts\Routing\Router;

/**
 * Class SocketRouter implements the routing contract for socket routing.
 *
 * @package Experus\Sockets\Core\Routing
 */
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
        return $this;
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
        return $this;
    }

    /**
     * Group a set of routes in the same channel.
     *
     * @param array $attributes The attributes of this channel.
     * @param Closure $callback
     * @return Router
     */
    public function channel(array $attributes = [], Closure $callback)
    {
        return $this;
    }
}
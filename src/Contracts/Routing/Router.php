<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Contracts\Routing;

use Closure;

/**
 * Interface Router defines a contract for a Websocket router.
 * @package Experus\Sockets\Contracts\Routing
 */
interface Router
{
    /**
     * Register the uri in the router.
     *
     * @param string $uri
     * @param string|array|Closure $action The action to perform when the route is called.
     * @return Router
     */
    public function socket($uri, $action);

    /**
     * Group a set of routes together.
     *
     * @param array $attributes The attributes to set on each route in the group.
     * @param Closure $callback
     * @return Router
     */
    public function group(array $attributes, Closure $callback);

    /**
     * Group a set of routes in the same channel.
     *
     * @param array $attributes The attributes of this channel.
     * @param Closure $callback
     * @return Router
     */
    public function channel(array $attributes = [], Closure $callback);
}
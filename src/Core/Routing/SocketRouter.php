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
     * Internal name for the global (unnamed) channel.
     */
    const GLOBAL_CHANNEL = '__global__';

    /**
     * All channels currently registered in the application.
     *
     * @var array
     */
    private $channels = [];

    /**
     * Reference to the current channel we're working in.
     *
     * @var array
     */
    private $current;

    public function __construct()
    {
        $this->channels[self::GLOBAL_CHANNEL] = [];
        $this->current = &$this->channels[self::GLOBAL_CHANNEL];
    }

    /**
     * Register the uri in the router.
     *
     * @param string $uri
     * @param string|array|Closure $action The action to perform when the route is called.
     * @return Router
     */
    public function socket($uri, $action)
    {
        $this->current[$uri] = $action;

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
     * @param array|string $attributes The attributes of this channel.
     * @param Closure $callback
     * @return Router
     */
    public function channel($attributes, Closure $callback)
    {
        $name = (is_array($attributes) ? $attributes['name'] : $attributes);

        if (!array_key_exists($name, $this->channels)) {
            $this->channels[$name] = [];
        }

        $this->current = &$this->channels[$name];

        $callback($this);

        $this->current = &$this->channels[self::GLOBAL_CHANNEL];

        return $this;
    }
}
<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Routing;

use Closure;
use Experus\Sockets\Contracts\Routing\Router;
use Experus\Sockets\Core\Server\SocketRequest;

/**
 * Class SocketRouter implements the routing contract for socket routing.
 *
 * @package Experus\Sockets\Core\Routing
 */
class SocketRouter implements Router
{
    /**
     * All registered routes.
     *
     * @var array
     */
    private $routes = [];

    /**
     * The currently active channel
     *
     * @var string
     */
    private $channel = '';

    /**
     * The currently active properties.
     *
     * @var array
     */
    private $properties = [];

    /**
     * Register the uri in the router.
     *
     * @param string $uri
     * @param string|array|Closure $action The action to perform when the route is called.
     * @return Router
     */
    public function socket($uri, $action)
    {
        $action = (is_array($action) ? $action : $action = ['uses' => $action]);

        $this->routes[] = new SocketRoute($uri, $action, $this->channel, $this->properties);

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
        $this->properties = $attributes;

        $callback($this);

        $this->properties = [];

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
        $this->channel = (is_array($attributes) ? $attributes['name'] : $attributes);

        $callback($this);

        $this->channel = '';

        return $this;
    }

    /**
     * Dispatch a route to the according handlers.
     *
     * @param SocketRequest $request
     * @return null The response.
     */
    public function dispatch(SocketRequest $request)
    {
        $route = array_first($this->routes, function (SocketRoute $route) use ($request) {
            return $route->match($request);
        });

        if (!is_null($route)) {
            // TODO dispatch route!
        }

        throw new \Exception('this should be a socket not found exception');
    }
}
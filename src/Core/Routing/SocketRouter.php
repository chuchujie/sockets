<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Routing;

use Closure;
use Experus\Sockets\Contracts\Routing\Router;
use Experus\Sockets\Core\Server\SocketRequest;
use Experus\Sockets\Exceptions\NotFoundException;
use Illuminate\Contracts\Foundation\Application;

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
     * The currently active properties.
     *
     * @var array
     */
    private $properties = [];

    /**
     * The laravel application.
     *
     * @var Application
     */
    private $app;

    /**
     * SocketRouter constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
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
        $action = (is_array($action) ? $action : $action = [SocketRoute::ACTION => $action]);

        $this->routes[] = new SocketRoute($uri, $action, $this->properties, $this->app);

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
        $original = $this->properties;

        $this->properties = array_merge($original, $attributes);

        $callback($this);

        $this->properties = $original;

        return $this;
    }

    /**
     * Dispatch a route to the according handlers.
     *
     * @param SocketRequest $request
     * @return array|null|object The response.
     * @throws NotFoundException When no matching route is found.
     */
    public function dispatch(SocketRequest $request)
    {
        $route = array_first($this->routes, function (SocketRoute $route) use ($request) {
            return $route->match($request);
        });

        if (!is_null($route)) {
            return $route->run($request);
        }

        throw new NotFoundException($request->path());
    }
}
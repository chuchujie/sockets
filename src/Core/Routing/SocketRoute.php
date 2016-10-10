<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Routing;

use Experus\Sockets\Core\Middlewares\MiddlewareDispatcher;
use Experus\Sockets\Core\Server\SocketRequest;
use RuntimeException;

class SocketRoute
{
    use MiddlewareDispatcher;

    /**
     * All the attributes of this route.
     *
     * @var array
     */
    private $attributes;

    /**
     * The URI for which the route is responsible.
     *
     * @var string
     */
    private $path;

    /**
     * SocketRoute constructor.
     * @param string $path
     * @param array $action
     * @param string $channel
     * @param array $attributes
     */
    public function __construct($path, array $action, $channel = '', $attributes = [])
    {
        $this->path = $path;
        $this->attributes = array_merge($attributes, $action, compact('channel'));
    }

    /**
     * Check if the request should be dispatched to this route.
     *
     * @param SocketRequest $request
     * @return bool
     */
    public function match(SocketRequest $request)
    {
        return false; // TODO perform some matchmaking magic to check if the route matches the request.
    }

    /**
     * Run the request through the handler and middlewares of this route.
     *
     * @param SocketRequest $request
     * @return array|null|object
     */
    public function run(SocketRequest $request)
    {
        if (!empty($this->middlewares())) {
            $response = $this->runThrough($this->middlewares(), $request); // TODO handle response from middleware stack?

            if (!is_null($response)) {
                return $response;
            }
        }

        if ($this->isControllerAction()) {
            return $this->dispatchController($request);
        }

        return $this->dispatchCallable($request);
    }

    /**
     * Get the name of the route if an alias is set, otherwise this function returns the route's path.
     *
     * @return string
     */
    public function name()
    {
        return $this->path;
    }

    /**
     * Check if this route is a controller action.
     *
     * @return bool
     */
    private function isControllerAction()
    {
        return is_string($this->attributes['uses']);
    }

    /**
     * Get the middlewares that should be applied to this route.
     *
     * @return array
     */
    private function middlewares()
    {
        if (isset($this->attributes['middlewares'])) {
            if (is_string($this->attributes['middlewares'])) {
                return [$this->attributes['middlewares']];
            }

            return $this->attributes['middlewares'];
        }

        return [];
    }

    /**
     * Dispatch the route to a controller method passed to this route.
     *
     * @param SocketRequest $request
     * @return array|null|object
     */
    private function dispatchController(SocketRequest $request)
    {
        // TODO some fancy container magic to resolve the dependencies and dispatch the controller
    }

    /**
     * Dispatch the route to the callable passed to this route.
     *
     * @param SocketRequest $request
     * @return array|null|object
     * @throws RuntimeException when a non-callable is passed as an action.
     */
    private function dispatchCallable(SocketRequest $request)
    {
        if (!is_callable($this->attributes['uses'])) {
            throw new RuntimeException('Invalid action passed to route ' . $this->name());
        }

        return $this->attributes['uses']($request); // TODO resolve callable parameters from the container.
    }
}
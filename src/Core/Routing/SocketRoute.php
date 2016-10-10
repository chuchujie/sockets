<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Routing;

use Experus\Sockets\Core\Middlewares\MiddlewareDispatcher;
use Experus\Sockets\Core\Server\SocketRequest;
use Experus\Sockets\Exceptions\InvalidActionException;
use Illuminate\Contracts\Foundation\Application;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
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
     * The laravel application
     *
     * @var Application
     */
    private $app;

    /**
     * SocketRoute constructor.
     * @param string $path
     * @param array $action
     * @param string $channel
     * @param array $attributes
     * @param Application $app
     */
    public function __construct($path, array $action, $channel = '', $attributes = [], Application $app)
    {
        $this->path = $path;
        $this->app = $app;
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
        return ($this->path == $request->path());
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
            $response = $this->runThrough($this->middlewares(), $request);

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
        if (!str_contains($this->attributes['uses'], '@')) {
            throw new InvalidActionException($this->name());
        }

        list($controller, $action) = explode('@', $this->attributes['uses']);

        $controller = $this->app->make($this->attributes['namespace'] . '\\' . $controller);
        $method = new ReflectionMethod($controller, $action);
        $parameters = $this->buildParameters($method, $request);

        return call_user_func_array([$controller, $action], $parameters);
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
            throw new InvalidActionException($this->name());
        }

        $method = new ReflectionFunction($this->attributes['uses']);

        $parameters = $this->buildParameters($method, $request);

        return $method->invokeArgs($parameters);
    }

    /**
     * Build an array of resolved parameters to call the method with.
     *
     * @param ReflectionFunctionAbstract $method
     * @param SocketRequest $request
     * @return array
     * @throws \ReflectionException
     */
    private function buildParameters(ReflectionFunctionAbstract $method, SocketRequest $request)
    {
        $parameters = [];

        foreach ($method->getParameters() as $parameter) {
            if ($parameter->hasType()) {
                if ($parameter->getType() == SocketRequest::class) {
                    $parameters[] = $request;
                } else {
                    $parameters[] = $this->app->make((string)$parameter->getType());
                }
            } else {
                if ($parameter->isDefaultValueAvailable()) {
                    $parameters[] = $parameter->getDefaultValue();
                } else {
                    throw new \ReflectionException('Cannot resolve ' . $parameter->getName() . ' for ' . $method->getName());
                }
            }
        }

        return $parameters;
    }
}
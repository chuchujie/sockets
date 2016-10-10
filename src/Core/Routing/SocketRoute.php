<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Routing;

use Experus\Sockets\Core\Middlewares\MiddlewareDispatcher;
use Experus\Sockets\Core\Server\SocketRequest;
use Illuminate\Contracts\Foundation\Application;
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
        $parts = explode('@', $this->attributes['uses']);

        if (sizeof($parts) !== 2) {
            throw new RuntimeException('Invalid action passed to route ' . $this->name());
        }

        $controller = $this->app->make($this->attributes['namespace'] . '\\' . $parts[0]);
        $method = new ReflectionMethod($controller, $parts[1]);
        $parameters = $this->buildParameters($method, $request);

        return call_user_func_array([$controller, $method], $parameters);
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

    /**
     * Build an array of resolved parameters to call the method with.
     *
     * @param ReflectionMethod $method
     * @param SocketRequest $request
     * @return array
     */
    private function buildParameters(ReflectionMethod $method, SocketRequest $request)
    {
        $parameters = [];

        foreach ($method->getParameters() as $parameter) {
            if ($parameter->hasType()) {
                if ($parameter->getType() == SocketRequest::class) {
                    $parameters[] = $request;
                } else {
                    $parameters[] = $this->app->make((string)$parameter->getType());
                }
            } else if ($parameter->isDefaultValueAvailable()) {
                $parameters[] = $parameter->getDefaultValue();
            } else {
                throw new \ReflectionException('Cannot resolve ' . $parameter->getName() . ' for ' . $method->getName());
            }
        }

        return $parameters;
    }
}
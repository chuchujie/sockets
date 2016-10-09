<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Routing;

use Experus\Sockets\Core\Middlewares\MiddlewareDispatcher;
use Experus\Sockets\Core\Server\SocketRequest;

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
     * SocketRoute constructor.
     * @param $path
     * @param $action
     * @param string $channel
     * @param array $attributes
     */
    public function __construct($path, $action, $channel = '', $attributes = [])
    {
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

        // run request callables.
    }

    /**
     * Get the middlewares that should be applied to this route.
     *
     * @return array
     */
    private function middlewares()
    {
        return []; // TODO extract middlewares for this route
    }
}
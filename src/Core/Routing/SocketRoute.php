<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Routing;

use Experus\Sockets\Core\Server\SocketRequest;

class SocketRoute
{
    /**
     * All the attributes of this route.
     *
     * @var array
     */
    private $attributes;

    public function __construct($path, $action, $channel = '', $attributes = [])
    {
        $this->attributes = array_merge($attributes, $action, compact('channel'));
    }

    public function match(SocketRequest $request)
    {
        return false;
    }
}
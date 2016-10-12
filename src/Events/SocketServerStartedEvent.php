<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Events;

use Experus\Sockets\Contracts\Server\Server;

/**
 * Class SocketServerStartedEvent is fired right before the event loop starts and the server is accepting connections.
 * @package Experus\Sockets\Events
 */
class SocketServerStartedEvent
{
    /**
     * @var Server
     */
    private $server;

    /**
     * SocketServerStartedEvent constructor.
     * @param Server $server
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Get the starting server.
     *
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }
}
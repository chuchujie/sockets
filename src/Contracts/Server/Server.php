<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Contracts\Server;

use Ratchet\MessageComponentInterface;
use Ratchet\WebSocket\WsServerInterface;

/**
 * Interface Server defines a contract for a websocket server.
 * @package Experus\Sockets\Contracts\Server
 */
interface Server extends MessageComponentInterface, WsServerInterface
{
    /**
     * Register a global middleware in the server.
     *
     * @param string $middleware
     */
    public function registerMiddleware($middleware);

    /**
     * Register a new protocol.
     *
     * @param string $name The name of the protocol.
     * @param string $protocol The protocol to register.
     */
    public function registerProtocol($name, $protocol);
}
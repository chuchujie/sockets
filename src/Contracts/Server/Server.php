<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Contracts\Server;

use Experus\Sockets\Contracts\Middlewares\Middleware;
use Experus\Sockets\Contracts\Protocols\Protocol;
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
     * @param Middleware $middleware
     */
    public function registerMiddleware(Middleware $middleware);

    /**
     * Register a new protocol.
     *
     * @param string $name The name of the protocol.
     * @param Protocol $protocol The protocol to register.
     */
    public function registerProtocol($name, Protocol $protocol);
}
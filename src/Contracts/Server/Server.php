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
}
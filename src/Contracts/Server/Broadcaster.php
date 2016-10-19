<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Contracts\Server;

use Closure;
use Experus\Sockets\Core\Client\SocketClient;

/**
 * Interface Broadcaster is a generic contract for providing broadcasting over multiple sockets.
 * @package Experus\Sockets\Contracts\Server
 */
interface Broadcaster
{
    /**
     * Get all connected clients on the broadcaster.
     *
     * @param Closure|null $filter filter which clients should be returned.
     * @return SocketClient[]
     */
    public function clients(Closure $filter = null);

    /**
     * Broadcast a message to all clients on the server.
     *
     * @param array|null|object $message
     * @param Closure|null $filter (optional) filter which clients should receive the message.
     */
    public function broadcast($message, Closure $filter = null);
}
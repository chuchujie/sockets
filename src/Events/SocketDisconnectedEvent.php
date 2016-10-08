<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Events;

use Experus\Sockets\Core\Client\SocketClient;

/**
 * Class SocketDisconnectedEvent is fired when (or before, depending on how the connection is closed) a client disconnects from the application.
 * @package Experus\Sockets\Events
 */
class SocketDisconnectedEvent
{
    /**
     * @var SocketClient
     */
    private $client;

    /**
     * SocketDisconnectedEvent constructor.
     * @param SocketClient $client
     */
    public function __construct(SocketClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get the disconnected client.
     * Writing to this client will not throw but it is not guaranteed the client receives the messages.
     *
     * @return SocketClient
     */
    public function getClient()
    {
        return $this->client;
    }
}
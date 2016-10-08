<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Events;

use Experus\Sockets\Core\Client\SocketClient;

/**
 * Class SocketConnectedEvent is fired when a client connects to the application.
 * @package Experus\Sockets\Events
 */
class SocketConnectedEvent
{
    /**
     * @var SocketClient
     */
    private $client;

    /**
     * SocketConnectedEvent constructor.
     * @param SocketClient $client
     */
    public function __construct(SocketClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get the freshly connected client.
     *
     * @return SocketClient
     */
    public function getClient()
    {
        return $this->client;
    }
}
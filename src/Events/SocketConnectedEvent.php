<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Events;

use Experus\Sockets\Core\Client\SocketClient;

class SocketConnectedEvent
{
    /**
     * @var SocketClient
     */
    private $client;

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
<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Events;

use Experus\Sockets\Core\Client\SocketClient;

class SocketDisconnectedEvent
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
     * @return SocketClient
     */
    public function getClient()
    {
        return $this->client;
    }
}
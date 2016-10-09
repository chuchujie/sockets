<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Server;

use Experus\Sockets\Core\Client\SocketClient;

class SocketRequest
{
    /**
     * The wrapped client.
     *
     * @var SocketClient
     */
    private $connection;

    /**
     * The raw payload send to us.
     *
     * @var string
     */
    private $message;

    public function __construct(SocketClient $connection, $message)
    {
        $this->connection = $connection;
        $this->message = $message;
    }

    /**
     * Get the protocol used for this request.
     *
     * @return string
     */
    public function protocol()
    {
        return $this->connection->protocol();
    }
}
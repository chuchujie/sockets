<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Server;

use Experus\Sockets\Core\Client\SocketClient;

class SocketRequest
{
    public function __construct(SocketClient $connection, $message)
    {
        $this->connection = $connection;
        $this->message = $message;
    }
}
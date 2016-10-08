<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Client;

use Ratchet\ConnectionInterface as Socket;

class SocketClient
{
    /**
     * The Ratchet socket.
     *
     * @var Socket
     */
    private $socket;

    /**
     * The UUID of this client.
     *
     * @var string
     */
    private $uuid;

    public function __construct(Socket $socket)
    {
        $this->socket = $socket;
        $this->uuid = uniqid('socket-', true);
    }

    public function write($data)
    {
        $data = json_encode($data);

        $this->socket->send($data);
    }

    public function close()
    {
        $this->socket->close();
    }

    /**
     * Get the UUID for this client
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    public function equals(Socket $socket)
    {
        return ($this->socket == $socket);
    }
}
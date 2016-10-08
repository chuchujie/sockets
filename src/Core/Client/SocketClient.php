<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Client;

use Ratchet\ConnectionInterface as Socket;

/**
 * Class SocketClient represents an open connection to a client.
 * @package Experus\Sockets\Core\Client
 */
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

    /**
     * SocketClient constructor.
     * @param Socket $socket the raw Ratchet socket to wrap.
     */
    public function __construct(Socket $socket)
    {
        $this->socket = $socket;
        $this->uuid = uniqid('socket-', true);
    }

    /**
     * Write data to the client.
     *
     * @param string|array|object $data
     */
    public function write($data)
    {
        $data = json_encode($data);

        $this->socket->send($data);
    }

    /**
     * Gracefully close the connection to the client.
     */
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

    /**
     * Compare a raw socket to the wrapped socket.
     *
     * @param Socket $socket
     * @return bool returns true if the raw sockets are equal.
     */
    public function equals(Socket $socket)
    {
        return ($this->socket == $socket);
    }
}
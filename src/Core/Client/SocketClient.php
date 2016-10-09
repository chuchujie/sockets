<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Client;

use Ratchet\ConnectionInterface as Socket;
use Ratchet\WebSocket\Version\RFC6455\Connection;
use RuntimeException;

/**
 * Class SocketClient represents an open connection to a client.
 * @package Experus\Sockets\Core\Client
 */
class SocketClient
{
    const PROTOCOL_HEADER = 'Sec-WebSocket-Protocol';
    const DEFAULT_PROTOCOL = 'experus';

    /**
     * The Ratchet socket.
     *
     * @var Connection
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
        if (!($socket instanceof Connection)) {
            throw new RuntimeException('Invalid protocol passed to internal client.'); // <- is our server wrapped in a WsServer?
        }

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

    /**
     * Get the protocol used for this socket.
     *
     * @return string
     */
    public function protocol()
    {
        $request = &$this->socket->WebSocket->request; // raw HTTP request used.

        if ($request->hasHeader(self::PROTOCOL_HEADER)) {
            return (string)$request->getHeader(self::PROTOCOL_HEADER);
        }

        return self::DEFAULT_PROTOCOL;
    }
}
<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Client;

use Ratchet\ConnectionInterface as Socket;
use Ratchet\WebSocket\Version\RFC6455\Connection;
use UnexpectedValueException;

/**
 * Class SocketClient represents an open connection to a client.
 * @package Experus\Sockets\Core\Client
 */
class SocketClient
{
    /**
     * The header to retrieve the UUID from the Websocket from.
     *
     * @var string
     */
    const UUID_HEADER = 'Sec-WebSocket-Key';

    /**
     * The header to retrieve the Websocket protocol used.
     *
     * @var string
     */
    const PROTOCOL_HEADER = 'Sec-WebSocket-Protocol';

    /**
     * The default protocol to use if none was specified.
     *
     * @var string
     */
    const DEFAULT_PROTOCOL = 'experus';

    /**
     * The Ratchet socket.
     *
     * @var Connection
     */
    private $socket;

    /**
     * SocketClient constructor.
     * @param Socket $socket the raw Ratchet socket to wrap.
     */
    public function __construct(Socket $socket)
    {
        if (!($socket instanceof Connection)) {
            throw new UnexpectedValueException('Invalid protocol passed to internal client'); // <- is our server wrapped in a WsServer?
        }

        $this->socket = $socket;
    }

    /**
     * Write data to the client.
     *
     * @param string|array|object $data
     */
    public function write($data)
    {
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
        return $this->header(self::UUID_HEADER);
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

    /**
     * Get the header from the request associated with this client.
     *
     * @param string $name The name of the header.
     * @return null|string returns the value of the header or null if the header does not exist.
     */
    public function header($name)
    {
        $request = &$this->socket->WebSocket->request; // raw HTTP request used.

        if ($request->hasHeader($name)) {
            return (string)$request->getHeader($name);
        }

        return null;
    }
}
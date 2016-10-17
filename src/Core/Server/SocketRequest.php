<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Server;

use Experus\Sockets\Contracts\Protocols\Protocol;
use Experus\Sockets\Core\Client\SocketClient;
use Illuminate\Session\SessionManager;

/**
 * Class SocketRequest provides access to the request a socket send to the server.
 * @package Experus\Sockets\Core\Server
 */
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

    /**
     * The protocol used to parse the message.
     *
     * @var Protocol
     */
    private $protocol;

    /**
     * SocketRequest constructor.
     * @param SocketClient $connection
     * @param $message
     * @param Protocol $protocol
     */
    public function __construct(SocketClient $connection, $message, Protocol $protocol)
    {
        $this->connection = $connection;
        $this->message = $message;
        $this->protocol = $protocol;
    }

    /**
     * Get the client that made the request.
     *
     * @return SocketClient
     */
    public function client()
    {
        return $this->connection;
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

    /**
     * Retrieve the body as an object or array.
     *
     * @return array|object|null
     */
    public function parse()
    {
        return $this->protocol->parse($this);
    }

    /**
     * Return the body (data) of the request.
     *
     * @return array|object|null|string
     */
    public function body()
    {
        return $this->protocol->body($this);
    }

    /**
     * Get the path this request was send to.
     *
     * @return string
     */
    public function path()
    {
        return $this->protocol->route($this);
    }

    /**
     * Get the raw string received from the client.
     *
     * @return string
     */
    public function raw()
    {
        return $this->message;
    }

    /**
     * Get the session for this request or retrieve a value from the session.
     *
     * @param string|null $key (optional) the key to retrieve from the session.
     * @return SessionManager|mixed
     */
    public function session($key = null)
    {
        return $this->connection->session($key);
    }

    /**
     * Get the header from the request.
     *
     * @param string $name The name of the header.
     * @return null|string returns the value of the header or null if the header does not exist.
     */
    public function header($name)
    {
        return $this->connection->header($name);
    }

    /**
     * Get the hostname that initiated the request.
     *
     * @return string
     */
    public function host()
    {
        return $this->connection->host();
    }
}
<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Server;

use Experus\Sockets\Contracts\Protocols\Protocol;
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
     * @param bool $assoc if true the result will be returned as an array, if false this functions returns an object.
     * @return array|object|null
     */
    public function json($assoc = false)
    {
        return json_decode($this->message, $assoc);
    }

    /**
     * Check if the body is valid JSON.
     *
     * @return bool
     */
    public function isJson()
    {
        return (strpos($this->message, '{', 0) === 0 || strpos($this->message, '[', 0) === 0);
    }

    /**
     * Return the raw body.
     *
     * @return string
     */
    public function body()
    {
        return $this->message;
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
}
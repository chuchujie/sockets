<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Server;

use Experus\Sockets\Core\Client\SocketClient;
use Experus\Sockets\Events\SocketConnectedEvent;
use Experus\Sockets\Events\SocketDisconnectedEvent;
use Illuminate\Contracts\Foundation\Application;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/**
 * Class SocketServer is an implementation of the Ratchet MessageComponentInterface and provides the connection layer between Laravel and Ratchet.
 * @package Experus\Sockets\Core\Server
 */
class SocketServer implements MessageComponentInterface
{
    /**
     * @var Application
     */
    private $app;

    /**
     * The connected clients.
     *
     * @var array
     */
    private $clients = [];

    /**
     * SocketServer constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $client = new SocketClient($conn);

        $this->clients[] = $client;

        $this->app->make('events')->fire(new SocketConnectedEvent($client));
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    public function onClose(ConnectionInterface $conn)
    {
        $client = $this->find($conn);

        $this->app->make('events')->fire(new SocketDisconnectedEvent($client));
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $payload = json_decode($msg, true);

        if (json_last_error() == JSON_ERROR_NONE) {
            // valid JSON, handle request here.
        }
    }

    /**
     * Find the client instance for a connection.
     *
     * @param ConnectionInterface $connection
     * @return SocketClient
     */
    private function find(ConnectionInterface $connection)
    {
        return array_first($this->clients, function(SocketClient $client) use ($connection) {
            return $client->equals($connection);
        });
    }
}
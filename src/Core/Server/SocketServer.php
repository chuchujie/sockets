<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Server;

use Experus\Sockets\Core\Client\SocketClient;
use Experus\Sockets\Events\SocketConnectedEvent;
use Experus\Sockets\Events\SocketDisconnectedEvent;
use Illuminate\Contracts\Foundation\Application;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

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

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
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
    function onClose(ConnectionInterface $conn)
    {
        $client = array_first($this->clients, function(SocketClient $client) use ($conn) { return $client->equals($conn); });

        $this->app->make('events')->fire(new SocketDisconnectedEvent($client));
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $from, $msg)
    {
    }
}
<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Server;

use Experus\Sockets\Contracts\Server\Server;
use Experus\Sockets\Core\Client\SocketClient;
use Experus\Sockets\Events\SocketConnectedEvent;
use Experus\Sockets\Events\SocketDisconnectedEvent;
use Illuminate\Contracts\Foundation\Application;
use Ratchet\ConnectionInterface;

/**
 * Class SocketServer is an implementation of the Ratchet MessageComponentInterface and provides the connection layer between Laravel and Ratchet.
 * @package Experus\Sockets\Core\Server
 */
class SocketServer implements Server
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
     * Global middleware stack.
     *
     * @var array
     */
    private $middlewares = [];

    /**
     * The registered protocols available.
     *
     * @var array
     */
    private $protocols = [];

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
        dd([$e->getMessage(), $e->getFile(), $e->getLine()]); // TODO proper error handling
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $client = $this->find($from);

        $protocol = $this->protocol($client);

        $request = new SocketRequest($client, $msg);
        dd($protocol);
    }

    /**
     * If any component in a stack supports a WebSocket sub-protocol return each supported in an array
     * @return array
     * @todo This method may be removed in future version (note that will not break code, just make some code obsolete)
     */
    public function getSubProtocols()
    {
        return array_keys($this->protocols);
    }

    /**
     * Register a global middleware in the server.
     *
     * @param string $middleware
     */
    public function registerMiddleware($middleware)
    {
        $this->middlewares[] = $this->app->make($middleware);
    }

    /**
     * Register a new protocol.
     *
     * @param string $name The name of the protocol.
     * @param string $protocol The protocol to register.
     */
    public function registerProtocol($name, $protocol)
    {
        $this->protocols[$name] = $protocol;
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

    private function protocol(SocketClient $client)
    {
        $protocol = array_first($this->protocols, function($class, $protocol) use ($client) {
            return $protocol == $client->protocol();
        });

        return $this->app->make($protocol);
    }
}
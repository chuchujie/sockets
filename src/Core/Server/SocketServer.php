<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Server;

use Exception;
use Experus\Sockets\Contracts\Exceptions\Handler;
use Experus\Sockets\Contracts\Middlewares\Stack;
use Experus\Sockets\Contracts\Protocols\Protocol;
use Experus\Sockets\Contracts\Routing\Router;
use Experus\Sockets\Contracts\Server\Server;
use Experus\Sockets\Core\Client\SocketClient;
use Experus\Sockets\Core\Middlewares\Pipeline;
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
     * The laravel application instance.
     *
     * @var Application
     */
    private $app;

    /**
     * The middleware stack.
     *
     * @var Stack
     */
    private $stack;

    /**
     * The middleware pipeline.
     *
     * @var Pipeline
     */
    private $pipeline;

    /**
     * The connected clients.
     *
     * @var array
     */
    private $clients = [];

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
        $this->stack = $this->app->make(Stack::class);
        $this->pipeline = new Pipeline();
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws Exception
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
     * @throws Exception
     */
    public function onClose(ConnectionInterface $conn)
    {
        $client = $this->find($conn);

        $this->app->make('events')->fire(new SocketDisconnectedEvent($client));

        $index = array_search($client, $this->clients);
        unset($this->clients[$index]);
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $connection
     * @param  Exception $exception
     * @throws Exception
     */
    public function onError(ConnectionInterface $connection, Exception $exception)
    {
        $client = $this->find($connection);
        $handler = $this->app->make(Handler::class);
        $response = $handler->handle($client, $exception);

        if (is_null($response)) {
            $client->write([
                'type' => get_class($exception),
                'message' => $exception->getMessage(),
                'location' => [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ],
                'trace' => $exception->getTraceAsString(),
            ]);
            $client->close();
        } else {
            $client->write($response);
        }
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws Exception
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $client = $this->find($from);
        $protocol = $this->protocol($client);
        $request = new SocketRequest($client, $msg, $protocol);
        $response = null;

        if (!empty($this->middlewares)) {
            $response = $this->pipeline->through($this->stack->resolve())->run($request);
        }

        $response = is_null($response) ? $this->app->make(Router::class)->dispatch($request) : $response;

        if (!is_null($response)) {
            $client->write($protocol->serialize($response));
        }
    }

    /**
     * If any component in a stack supports a WebSocket sub-protocol return each supported in an array
     * @return array
     */
    public function getSubProtocols()
    {
        return array_keys($this->protocols);
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
        return array_first($this->clients, function (SocketClient $client) use ($connection) {
            return $client->equals($connection);
        });
    }

    /**
     * Get the protocol the client uses.
     *
     * @param SocketClient $client
     * @return Protocol
     */
    private function protocol(SocketClient $client)
    {
        $protocol = array_first($this->protocols, function ($_, $protocol) use ($client) {
            return $protocol == $client->protocol();
        });

        return $this->app->make($protocol);
    }
}
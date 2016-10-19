<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Server;

use Closure;
use Exception;
use Experus\Sockets\Contracts\Exceptions\Handler;
use Experus\Sockets\Contracts\Middlewares\Stack;
use Experus\Sockets\Contracts\Protocols\Protocol;
use Experus\Sockets\Contracts\Routing\Router;
use Experus\Sockets\Contracts\Server\Broadcaster;
use Experus\Sockets\Contracts\Server\Server;
use Experus\Sockets\Core\Client\SocketClient;
use Experus\Sockets\Core\Middlewares\Pipeline;
use Experus\Sockets\Core\Session\SocketSessionFactory;
use Experus\Sockets\Events\SocketConnectedEvent;
use Experus\Sockets\Events\SocketDisconnectedEvent;
use Experus\Sockets\Exceptions\ProtocolNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Ratchet\ConnectionInterface;
use RuntimeException;

/**
 * Class SocketServer is an implementation of the Ratchet MessageComponentInterface and provides the connection layer between Laravel and Ratchet.
 * @package Experus\Sockets\Core\Server
 */
class SocketServer implements Server, Broadcaster
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
        $session = $this->app->make(SocketSessionFactory::class); // the session for this connection
        $client = new SocketClient($conn, $session);
        $protocol = $this->protocol($client);
        $client->setProtocol($protocol);

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

        if (is_null($client)) { // client closed by error handling due exception in initialization.
            return;
        }

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

        if (is_null($client)) { // client has thrown exception *while* connecting
            return $connection->close();
        }

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
        $request = new SocketRequest($client, $msg);
        $response = null;

        if (!empty($this->middlewares)) {
            $response = $this->pipeline->through($this->stack->resolve())->run($request);
        }

        $response = is_null($response) ? $this->app->make(Router::class)->dispatch($request) : $response;

        if (!is_null($response)) {
            $client->write($response);
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
     * @throws ProtocolNotFoundException
     */
    private function protocol(SocketClient $client)
    {
        if (empty($this->protocols)) {
            throw new RuntimeException('No protocols registered.');
        }

        $protocol = array_first($this->protocols, function ($_, $protocol) use ($client) {
            $header = $client->header(SocketClient::PROTOCOL_HEADER);
            return $protocol == (is_null($header) ? SocketClient::DEFAULT_PROTOCOL : $header);
        });

        if (is_null($protocol)) {
            throw new ProtocolNotFoundException($client->protocol());
        }

        return $this->app->make($protocol);
    }

    /**
     * Get all connected clients on the broadcaster.
     *
     * @param Closure|null $filter filter which clients should be returned.
     * @return SocketClient[]
     */
    public function clients(Closure $filter = null)
    {
        if (is_null($filter)) {
            return $this->clients;
        }

        return array_filter($this->clients, $filter);
    }

    /**
     * Broadcast a message to all clients on the server.
     *
     * @param array|null|object $message
     * @param Closure|null $filter (optional) filter which clients should receive the message.
     */
    public function broadcast($message, Closure $filter = null)
    {
        foreach ($this->clients($filter) as $client) {
            $client->write($message);
        }
    }
}
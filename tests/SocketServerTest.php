<?php

// Created by dealloc. All rights reserved.

use Experus\Sockets\Contracts\Protocols\Protocol;
use Experus\Sockets\Core\Server\SocketServer;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Mockery as m;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\Version\RFC6455\Connection;

/**
 * Class SocketServerTest tests the \Experus\Sockets\Core\Server\SocketServer class.
 */
class SocketServerTest extends TestCase
{
    /**
     * The mocked laravel application instance we're testing with.
     *
     * @var Application|m\MockInterface
     */
    protected $app;

    /**
     * The mocked eventbus
     *
     * @var Dispatcher|m\Expectation
     */
    private $events;

    /**
     * The SocketServer we're testing with.
     *
     * @var SocketServer
     */
    protected $server;

    /**
     * Set up the testing environment.
     */
    public function setUp()
    {
        $this->events = m::mock('events')->shouldReceive('fire')->andReturnNull();
        $this->app = m::mock(Application::class);
        $this->server = new SocketServer($this->app);
    }

    /**
     * Test if we can register a protocol.
     *
     * @test
     */
    public function registerProtocols()
    {
        $protocol = m::mock(Protocol::class);

        $this->server->registerProtocol('testprotocol', $protocol);

        assert(sizeof($this->server->getSubProtocols()) == 1, 'Server should have one protocol registered.');
        self::assertEquals($this->server->getSubProtocols()[0], 'testprotocol');
    }

    /**
     * Test if the connect event fires when a client connects.
     *
     * @test
     */
    public function clientConnects()
    {
        $this->app->shouldReceive('make')
            ->with('events')
            ->andReturn($this->events->once()->mock());

        $this->server->onOpen(m::mock(Connection::class));
    }

    /**
     * Test if the client only allows WebSocket decorated requests.
     *
     * @test
     */
    public function clientAcceptsOnlyRFC6455()
    {
        $this->app->shouldReceive('make')
            ->never();

        $this->expectException(UnexpectedValueException::class);

        $this->server->onOpen(m::mock(ConnectionInterface::class));
    }

    /**
     * Test if the close event is fired when closing a connection.
     *
     * @test
     */
    public function clientClosesConnection()
    {
        $connection = m::mock(Connection::class);
        $connection->shouldReceive('write')->andThrow(RuntimeException::class);

        $this->app->shouldReceive('make')
            ->with('events')
            ->andReturn($this->events->twice()->mock());

        $this->server->onOpen($connection);

        $this->server->onClose($connection);
    }

    /**
     * Test if we can send a message to a client.
     *
     * @test
     */
    public function message()
    {
        // TODO we need to be able to mock public properties (to mock the WebSocket decorator)
    }

    /**
     * Test if we can send a message when there are multiple protocols registered.
     * The server should automatically pick the correct protocol and transform the response accordingly.
     *
     * @test
     */
    public function messageMultipleProtocols()
    {
        // TODO we need to be able to mock public properties (to mock the WebSocket decorator)
    }

    /**
     * Check if we can register middleware on the server.
     *
     * @test
     */
    public function registerMiddleware()
    {
        // TODO we need to be able to mock public properties (to mock the WebSocket decorator)
    }

    /**
     * Test if we can respond early from middleware.
     *
     * @test
     */
    public function messageFromMiddleware()
    {
        // TODO we need to be able to mock public properties (to mock the WebSocket decorator)
    }

    /**
     * Test if we can let a message pass through a middleware but not respond from it.
     *
     * @test
     */
    public function messageThroughMiddleware()
    {
        // TODO we need to be able to mock public properties (to mock the WebSocket decorator)
    }
}

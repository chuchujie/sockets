<?php

// Created by dealloc. All rights reserved.

use Experus\Sockets\Contracts\Protocols\Protocol;
use Experus\Sockets\Core\Server\SocketServer;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Mockery as m;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\Version\RFC6455\Connection;

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

    public function setUp()
    {
        $this->events = m::mock('events')->shouldReceive('fire')->andReturnNull();
        $this->app = m::mock(Application::class);
        $this->server = new SocketServer($this->app);
    }

    /**
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
     * @test
     */
    public function message()
    {
        // TODO we need to be able to mock public properties (to mock the WebSocket decorator)
    }

    /**
     * @test
     */
    public function messageMultipleProtocols()
    {
        // TODO we need to be able to mock public properties (to mock the WebSocket decorator)
    }

    /**
     * @test
     */
    public function registerMiddleware()
    {
        // TODO we need to be able to mock public properties (to mock the WebSocket decorator)
    }

    /**
     * @test
     */
    public function messageFromMiddleware()
    {
        // TODO we need to be able to mock public properties (to mock the WebSocket decorator)
    }

    /**
     * @test
     */
    public function messageThroughMiddleware()
    {
        // TODO we need to be able to mock public properties (to mock the WebSocket decorator)
    }
}

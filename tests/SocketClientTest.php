<?php

// Created by dealloc. All rights reserved.

use Experus\Sockets\Core\Client\SocketClient;
use Guzzle\Http\Message\RequestInterface;
use Mockery as m;
use Ratchet\WebSocket\Version\RFC6455\Connection;

/**
 * Class SocketClientTest tests the \Experus\Sockets\Core\Client\SocketClient class
 */
class SocketClientTest extends TestCase
{
    /**
     * The mockery socket we're testing with.
     *
     * @var Connection|\Mockery\MockInterface
     */
    protected $socket;

    /**
     * The mocked internal request we're testing with.
     *
     * @var RequestInterface|m\MockInterface
     */
    protected $request;

    /**
     * Set up the testing environment.
     */
    public function setUp()
    {
        $this->socket = m::mock('\Ratchet\WebSocket\Version\RFC6455\Connection');

        $websocket = new stdClass;
        $this->request = m::mock(RequestInterface::class);
        $websocket->request = $this->request;
        $this->setMagicProperty($this->socket, 'WebSocket', $websocket);
    }

    /**
     * Test writing a string to the socket.
     *
     * @test
     */
    public function writeStringToSocket()
    {
        $this->socket
            ->shouldReceive('send')
            ->with('hello world')
            ->andReturnUndefined()
            ->once();

        $client = new SocketClient($this->socket);

        $client->write('hello world');
    }

    /**
     * Test closing the socket.
     *
     *
     * @test
     */
    public function closeSocket()
    {
        $this->socket
            ->shouldReceive('close')
            ->andReturnUndefined()
            ->once();

        $client = new SocketClient($this->socket);

        $client->close();
    }

    /**
     * Test if the socket retrieves the websocket key.
     *
     * @test
     */
    public function retrievesUUID()
    {
        $client = new SocketClient($this->socket);

        $this->request->shouldReceive('hasHeader')
            ->with(SocketClient::UUID_HEADER)
            ->andReturn(true)
            ->once();

        $this->request->shouldReceive('getHeader')
            ->with(SocketClient::UUID_HEADER)
            ->andReturn('uuid')
            ->once();

        self::assertEquals($client->getUuid(), 'uuid');
    }

    /**
     * Test if the same raw socket is equal when compared to the same socket wrapped in a client.
     *
     * @test
     */
    public function equals()
    {
        $client = new SocketClient($this->socket);

        assert($client->equals($this->socket), 'Same connection should be equal');
    }

    /**
     * Test if different connections are compared through the SocketClient they are not considered equal.
     *
     * @test
     */
    public function equalsDifferentConnections()
    {
        $client = new SocketClient($this->socket);
        $different = m::mock('\Ratchet\WebSocket\Version\RFC6455\Connection');

        assert($client->equals($different), 'Different connections should not be considered equal.');
    }

    /**
     * Tests if the SocketClient can deal with connections that do not specify a protocol.
     * @test
     */
    public function withoutProtocol()
    {
        $websocket = new stdClass;
        $websocket->request = m::mock('\Guzzle\Http\Message\RequestInterface');
        $websocket->request->shouldReceive('hasHeader')
            ->with(SocketClient::PROTOCOL_HEADER)
            ->andReturn(false)
            ->once();

        $this->setMagicProperty($this->socket, 'WebSocket', $websocket);

        $client = new SocketClient($this->socket);

        self::assertEquals($client->protocol(), SocketClient::DEFAULT_PROTOCOL);
    }

    /**
     * Test if the SocketClient can deal with connections that specify a protocol.
     * @test
     */
    public function withProtocol()
    {
        $this->request->shouldReceive('hasHeader')
            ->with(SocketClient::PROTOCOL_HEADER)
            ->andReturn(true)
            ->once();
        $this->request->shouldReceive('getHeader')
            ->with(SocketClient::PROTOCOL_HEADER)
            ->andReturn('FOO')
            ->once();

        $client = new SocketClient($this->socket);

        self::assertEquals($client->protocol(), 'FOO');
    }
}

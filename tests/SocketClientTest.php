<?php

// Created by dealloc. All rights reserved.

use Experus\Sockets\Core\Client\SocketClient;
use Mockery as m;

/**
 * Class SocketClientTest tests the SocketClient class
 */
class SocketClientTest extends TestCase
{
    /**
     * The mockery socket we're testing with.
     *
     * @var \Mockery\MockInterface
     */
    protected $socket;

    /**
     * Set up the testing environment.
     */
    public function setUp()
    {
        $this->socket = m::mock('\Ratchet\WebSocket\Version\RFC6455\Connection');
    }

    /**
     * Test writing a string to the socket.
     */
    public function testWriteStringToSocket()
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
     */
    public function testCloseSocket()
    {
        $this->socket
            ->shouldReceive('close')
            ->andReturnUndefined()
            ->once();

        $client = new SocketClient($this->socket);

        $client->close();
    }

    /**
     * Test if the socket generates a unique identifier for each connection.
     */
    public function testGeneratesUUID()
    {
        $client = new SocketClient($this->socket);
        $client2 = new SocketClient($this->socket);

        self::assertNotEquals($client->getUuid(), $client2->getUuid(), 'Socket clients should all have a unique ID');
    }

    /**
     * Test if the same raw socket is equal when compared to the same socket wrapped in a client.
     */
    public function testEquals()
    {
        $client = new SocketClient($this->socket);

        assert($client->equals($this->socket), 'Same connection should be equal');
    }

    /**
     * Test if different connections are compared through the SocketClient they are not considered equal.
     */
    public function testEqualsDifferentConnections()
    {
        $client = new SocketClient($this->socket);
        $different = m::mock('\Ratchet\WebSocket\Version\RFC6455\Connection');

        assert($client->equals($different), 'Different connections should not be considered equal.');
    }

    /**
     * Tests if the SocketClient can deal with connections that do not specify a protocol.
     * @todo Figure out how to set a public property, Ratchet somehow messes with the magic method making it very hard to set properties on the mock.
     */
    public function _testWithoutProtocol()
    {
        $websocket = new stdClass;
        $websocket->request = m::mock('\Guzzle\Http\Message\RequestInterface');
        $websocket->request->shouldReceive('hasHeader')
            ->with(SocketClient::PROTOCOL_HEADER)
            ->andReturn(false)
            ->once();

        $this->socket->WebSocket = $websocket; // FFS

        $client = new SocketClient($this->socket);

        self::assertEquals($client->protocol(), SocketClient::DEFAULT_PROTOCOL);
    }
}

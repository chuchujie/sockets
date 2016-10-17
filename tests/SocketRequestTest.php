<?php

// Created by dealloc. All rights reserved.

use Experus\Sockets\Contracts\Protocols\Protocol;
use Experus\Sockets\Core\Client\SocketClient;
use Experus\Sockets\Core\Server\SocketRequest;
use Experus\Sockets\Exceptions\ParseException;
use Mockery as m;

/**
 * Class SocketRequestTest tests the \Experus\Sockets\Core\Server\SocketRequest class.
 */
class SocketRequestTest extends TestCase
{
    /**
     * A well formed JSON string.
     *
     * @var string
     */
    const JSON_BODY = '{"hello":"world"}';

    /**
     * A malformed JSON string.
     *
     * @var string
     */
    const MALFORMED_JSON_BODY = '{"hello":"world""}';

    /**
     * A well formed XML string.
     *
     * @var string
     */
    const XML_BODY = '<root><body>hello</body></root>';

    /**
     * A malformed XML string.
     *
     * @var string
     */
    const MALFORMED_XML_BODY = '<<root><body>hello</body></root>';

    /**
     * The mocked protocol we're testing with.
     *
     * @var m\MockInterface|Protocol
     */
    private $protocol;

    /**
     * The mocked client we're testing with.
     *
     * @var SocketClient|m\MockInterface
     */
    protected $client;

    /**
     * The request we're testing against.
     *
     * @var SocketRequest
     */
    protected $request;

    /**
     * Set up the testing environment.
     */
    public function setUp()
    {
        $this->client = m::mock(SocketClient::class);
        $this->protocol = m::mock(Protocol::class);
    }

    /**
     * Test if we can create a request with an empty body.
     *
     * @test
     */
    public function createRequestEmptyBody()
    {
        $this->request = new SocketRequest($this->client, '', $this->protocol);
    }

    /**
     * Test if we can create a request with null as body.
     *
     * @test
     */
    public function createRequestNullBody()
    {
        $this->request = new SocketRequest($this->client, null, $this->protocol);
    }

    /**
     * Test if we can create a client with a JSON string body.
     *
     * @test
     */
    public function createRequestJsonBody()
    {
        $this->request = new SocketRequest($this->client, self::JSON_BODY, $this->protocol);
    }

    /**
     * Test if we can create a client with a malformed JSON string body.
     *
     * @test
     */
    public function createRequestMalformedJsonBody()
    {
        $this->request = new SocketRequest($this->client, self::MALFORMED_JSON_BODY, $this->protocol);
    }

    /**
     * Test if we can create a request with an XML string body.
     *
     * @test
     */
    public function createRequestXmlBody()
    {
        $this->request = new SocketRequest($this->client, self::XML_BODY, $this->protocol);
    }

    /**
     * Test if we can create a request with a malformed XML string body.
     *
     * @test
     */
    public function createRequestMalformedXmlBody()
    {
        $this->request = new SocketRequest($this->client, self::MALFORMED_XML_BODY, $this->protocol);
    }

    /**
     * Test if we can parse a request with a JSON string body.
     *
     * @test
     */
    public function parseJsonBody()
    {
        $this->createRequestJsonBody();

        $this->protocol->shouldReceive('parse')
            ->with($this->request)
            ->andReturn(json_decode(self::JSON_BODY));

        self::assertEquals($this->request->parse(), json_decode(self::JSON_BODY));
    }

    /**
     * Test if we can parse a request with a malformed JSON string body.
     * This should throw a ParseException since the body cannot be parsed.
     *
     * @test
     */
    public function paseMalformedJsonBody()
    {
        $this->createRequestMalformedJsonBody();

        $this->protocol->shouldReceive('parse')
            ->with($this->request)
            ->andThrow(ParseException::class);

        $this->expectException(ParseException::class);

        $this->request->parse();
    }

    /**
     * Test if we can parse a request with a XML string body.
     *
     * @test
     */
    public function parseXmlBody()
    {
        $this->createRequestXmlBody();

        $this->protocol->shouldReceive('parse')
            ->with($this->request)
            ->andReturn(simplexml_load_string(self::XML_BODY));

        $response = $this->request->parse();
        self::assertEquals($this->request->parse(), simplexml_load_string(self::XML_BODY));
        self::assertInstanceOf(SimpleXMLElement::class, $response, 'Parsed XML should be SimpleXMLElement');
    }

    /**
     * Test if we can parse a request with a malformed XML string body.
     * This should throw a ParseException since the body cannot be parsed.
     *
     * @test
     */
    public function parseMalformedXmlBody()
    {
        $this->createRequestMalformedXmlBody();

        $this->protocol->shouldReceive('parse')
            ->with($this->request)
            ->andThrow(ParseException::class);

        $this->expectException(ParseException::class);

        $this->request->parse();
    }

    /**
     * Test if we can retrieve the raw string representation of what the client sent.
     *
     * @test
     */
    public function retrieveRawBody()
    {
        $this->createRequestJsonBody();

        self::assertEquals($this->request->raw(), self::JSON_BODY);
    }

    /**
     * Test if we can retrieve the payload of the request (parsed body).
     *
     * @test
     */
    public function retrieveBody()
    {
        $this->createRequestJsonBody();

        $this->protocol->shouldReceive('body')
            ->with($this->request)
            ->andReturn(json_decode(self::JSON_BODY))
            ->once();

        $this->request->body();
    }

    /**
     * Test if we can retrieve the connection from the request.
     *
     * @test
     */
    public function retrieveConnection()
    {
        $this->createRequestEmptyBody();

        self::assertEquals($this->request->client(), $this->client);
    }

    /**
     * Test if we can receive the session manager.
     *
     * @test
     */
    public function retrieveSessionManager()
    {
        $this->createRequestEmptyBody();

        $this->client->shouldReceive('session')
            ->with(null)
            ->andReturn('SESSIONMANAGER')
            ->once();

        self::assertEquals($this->request->session(), 'SESSIONMANAGER');
    }

    /**
     * Test if we can retrieve a session variable.
     *
     * @test
     */
    public function retrieveSessionVariable()
    {
        $this->createRequestEmptyBody();

        $this->client->shouldReceive('session')
            ->with('foo')
            ->andReturn('bar')
            ->once();

        self::assertEquals($this->request->session('foo'), 'bar');
    }
}

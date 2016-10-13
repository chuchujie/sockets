<?php

// Created by dealloc. All rights reserved.

use Experus\Sockets\Contracts\Protocols\Protocol;
use Experus\Sockets\Core\Client\SocketClient;
use Experus\Sockets\Core\Server\SocketRequest;
use Mockery as m;

class SocketRequestTest extends TestCase
{
    const JSON_BODY = '{"hello":"world"}';
    const MALFORMED_JSON_BODY = '{"hello":"world""}';
    const XML_BODY = '<root><body>hello</body></root>';
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

    public function setUp()
    {
        $this->client = null;
        $this->client = m::mock(SocketClient::class);
        $this->protocol = m::mock(Protocol::class);
    }

    /**
	 * @test
	 */
	public function createRequestEmptyBody()
    {
        $this->request = new SocketRequest($this->client, '', $this->protocol);
    }

    /**
	 * @test
	 */
	public function createRequestNullBody()
    {
        $this->request = new SocketRequest($this->client, null, $this->protocol);
    }

    /**
	 * @test
	 */
	public function createRequestJsonBody()
    {
        $this->request = new SocketRequest($this->client, self::JSON_BODY, $this->protocol);
    }

    /**
	 * @test
	 */
	public function createRequestMalformedJsonBody()
    {
        $this->request = new SocketRequest($this->client, self::MALFORMED_JSON_BODY, $this->protocol);
    }

    /**
	 * @test
	 */
	public function createRequestXmlBody()
    {
        $this->request = new SocketRequest($this->client, self::XML_BODY, $this->protocol);
    }

    /**
	 * @test
	 */
	public function createRequestMalformedXmlBody()
    {
        $this->request = new SocketRequest($this->client, self::MALFORMED_XML_BODY, $this->protocol);
    }

    /**
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
	 * @test
	 */
	public function paseMalformedJsonBody()
    {
        $this->createRequestMalformedJsonBody();

        $this->protocol->shouldReceive('parse')
            ->with($this->request)
            ->andReturn(json_decode(self::JSON_BODY));

        // TODO when parsing throws exception by contract, uncomment this.
//        $this->expectException(RuntimeException::class);
        self::assertEquals($this->request->parse(), json_decode(self::JSON_BODY));
    }

    /**
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
	 * @test
	 */
	public function parseMalformedXmlBody()
    {
        $this->createRequestMalformedXmlBody();

        $this->protocol->shouldReceive('parse')
            ->with($this->request)
            ->andReturn(@simplexml_load_string(self::MALFORMED_XML_BODY));

        // TODO when parsing throws exception by contract, uncomment this.
//        $this->expectException(RuntimeException::class);

        $response = $this->request->parse();
        self::assertEquals($response, @simplexml_load_string(self::MALFORMED_XML_BODY));
        self::assertNotInstanceOf(SimpleXMLElement::class, $response, 'Invalid XML should not be parsed');
    }

    /**
	 * @test
	 */
	public function retrieveRawBody()
    {
        $this->createRequestJsonBody();

        self::assertEquals($this->request->raw(), self::JSON_BODY);
    }
}

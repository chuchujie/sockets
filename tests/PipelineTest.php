<?php

// Created by dealloc. All rights reserved.

use Experus\Sockets\Contracts\Middlewares\Middleware;
use Experus\Sockets\Core\Middlewares\Pipeline;
use Experus\Sockets\Core\Server\SocketRequest;
use Mockery as m;

class PipelineTest extends TestCase
{
    /**
     * A mock middleware that passes requests through.
     *
     * @var Middleware|m\MockInterface
     */
    protected $through;

    /**
     * A mock middleware that returns prematurely.
     *
     * @var Middleware|m\MockInterface
     */
    protected $block;

    /**
     * A mock request we're testing with.
     *
     * @var SocketRequest|m\MockInterface
     */
    protected $request;

    public function setUp()
    {
        $this->through = m::mock(\Mocks\MockMiddleware::class);
        $this->block = m::mock(\Mocks\BlockMiddleware::class);
        $this->request = m::mock(SocketRequest::class);
    }

    /**
     * @test
     */
    public function setStackOnStream()
    {
        (new Pipeline())->through([$this->through, $this->block]);
    }

    /**
     * @test
     */
    public function setEmptyStackOnStream()
    {
        (new Pipeline())->through([]);
    }

    /**
     * @test
     */
    public function runThroughStack()
    {
        $pipeline = (new Pipeline())->through([$this->through]);

        $this->through->shouldReceive('handle')
            ->withAnyArgs()
            ->once();

        self::assertNull($pipeline->run($this->request));
    }

    /**
     * @test
     */
    public function runThroughStackReturnEarly()
    {
        $pipeline = (new Pipeline())->through([$this->block]);

        $this->block->shouldReceive('handle')
            ->withAnyArgs()
            ->andReturn(\Mocks\BlockMiddleware::RESPONSE_TEXT)
            ->once();

        self::assertEquals(\Mocks\BlockMiddleware::RESPONSE_TEXT, $pipeline->run($this->request));
    }

    /**
     * @test
     */
    public function runThroughEmptyStack()
    {
        $pipeline = new Pipeline;

        self::assertNull($pipeline->run($this->request));
    }
}

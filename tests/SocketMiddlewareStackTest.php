<?php

// Created by dealloc. All rights reserved.

use Experus\Sockets\Contracts\Middlewares\Stack;
use Experus\Sockets\Core\Middlewares\SocketMiddlewareStack;
use Experus\Sockets\Exceptions\MiddlewareNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Mockery as m;

/**
 * Class SocketMiddlewareStackTest tests the \Experus\Sockets\Core\Middlewares\SocketMiddlewareStack class.
 */
class SocketMiddlewareStackTest extends TestCase
{
    /**
     * The mocked laravel application instance.
     *
     * @var m\MockInterface|Application
     */
    protected $app;

    /**
     * The SocketMiddleware stack we're testing against.
     *
     * @var SocketMiddlewareStack
     */
    protected $stack;

    /**
     * Set up the testing environment.
     */
    public function setUp()
    {
        $this->app = m::mock(Application::class);
        $this->stack = new SocketMiddlewareStack($this->app);
    }

    /**
     * Tests if we can register an alias.
     *
     * @test
     */
    public function registerAlias()
    {
        $this->stack->register('alias', \Mocks\MockMiddleware::class);
    }

    /**
     * Test if we can register a group.
     *
     * @test
     */
    public function registerGroup()
    {
        $this->stack->register('alias_group', [\Mocks\MockMiddleware::class]);
    }

    /**
     * Tesst if we can register in the global middleware stack.
     *
     * @test
     */
    public function registerGlobal()
    {
        $this->stack->register(\Mocks\MockMiddleware::class);
    }

    /**
     * Test if we can resolve a middleware by ::class reference.
     *
     * @test
     */
    public function resolveByClassReference()
    {
        $mock = m::mock(\Mocks\MockMiddleware::class);
        $this->app->shouldReceive('make')
            ->with(\Mocks\MockMiddleware::class)
            ->andReturn($mock)
            ->once();

        $stack = new SocketMiddlewareStack($this->app);

        self::assertEquals($stack->resolve(\Mocks\MockMiddleware::class), $mock);
    }

    /**
     * Test if we can resolve a class reference that doesn't exist.
     *
     * @test
     */
    public function resolveByClassReferenceNotExist()
    {
        $this->app->shouldReceive('make')
            ->with('\Unexisting')
            ->andThrow(ReflectionException::class)
            ->once();

        $this->expectException(ReflectionException::class);

        $stack = new SocketMiddlewareStack($this->app);
        $stack->resolve('\Unexisting');
    }

    /**
     * Test if we can resolve an alias.
     *
     * @test
     */
    public function resolveAlias()
    {
        $mock = m::mock(\Mocks\MockMiddleware::class);
        $this->app->shouldReceive('make')
            ->with(\Mocks\MockMiddleware::class)
            ->andReturn($mock)
            ->once();

        $this->stack->register('alias', $mock);

        self::assertEquals($this->stack->resolve('alias'), $mock);
    }

    /**
     * Test if we can resolve an alias that doesn't exist.
     *
     * @test
     */
    public function resolveAliasNotExist()
    {
        $this->expectException(MiddlewareNotFoundException::class);

        $this->stack->resolve('alias');
    }

    /**
     * Test if we can resolve a group.
     *
     * @test
     */
    public function resolveGroup()
    {
        $mock = m::mock(\Mocks\MockMiddleware::class);
        $this->app->shouldReceive('make')
            ->with(\Mocks\MockMiddleware::class)
            ->andReturn($mock)
            ->once();

        $this->stack->register('group', [\Mocks\MockMiddleware::class]);

        self::assertEquals($this->stack->resolve('group'), [$mock]);
    }

    /**
     * Test if we can resolve a group with an alias in it.
     *
     * @test
     */
    public function resolveGroupWithAlias()
    {
        $mock = m::mock(\Mocks\MockMiddleware::class);
        $this->app->shouldReceive('make')
            ->with(\Mocks\MockMiddleware::class)
            ->andReturn($mock)
            ->once();

        $this->stack->register('alias', \Mocks\MockMiddleware::class);
        $this->stack->register('group', ['alias']);

        self::assertEquals($this->stack->resolve('group'), [$mock]);
    }

    /**
     * Test if we can resolve a group that contains a reference to another group.
     *
     * @test
     */
    public function resolveNestedGroup()
    {
        $mock = m::mock(\Mocks\MockMiddleware::class);
        $this->app->shouldReceive('make')
            ->with(\Mocks\MockMiddleware::class)
            ->andReturn($mock)
            ->once();

        $this->stack->register('group1', [\Mocks\MockMiddleware::class]);
        $this->stack->register('group2', ['group1']);

        self::assertEquals($this->stack->resolve('group2'), [$mock]);
    }

    /**
     * Test if we can resolve nested groups and eliminate duplicates.
     *
     * @test
     * @todo optimize so that mockmiddleware is only resolved once.
     */
    public function resolveNestedGroupWithDuplicates()
    {
        $mock = m::mock(\Mocks\MockMiddleware::class);
        $this->app->shouldReceive('make')
            ->with(\Mocks\MockMiddleware::class)
            ->andReturn($mock)
            ->twice();

        $this->stack->register('alias', \Mocks\MockMiddleware::class);
        $this->stack->register('group1', ['alias', \Mocks\MockMiddleware::class]);
        $this->stack->register('group2', ['group1']);

        self::assertEquals($this->stack->resolve('group2'), [$mock]);
    }

    /**
     * Test if we can resolve a group that doesn't exist.
     *
     * @test
     */
    public function resolveGroupNotExist()
    {
        $this->expectException(MiddlewareNotFoundException::class);

        $this->stack->resolve('group');
    }

    /**
     * Test if we can resolve the global stack.
     *
     * @test
     */
    public function resolveGlobal()
    {
        $mock = m::mock(\Mocks\MockMiddleware::class);
        $this->app->shouldReceive('make')
            ->with(\Mocks\MockMiddleware::class)
            ->andReturn($mock)
            ->once();

        $this->stack->register(\Mocks\MockMiddleware::class);

        self::assertEquals($this->stack->resolve(Stack::GLOBAL_STACK), [$mock]);
    }
}

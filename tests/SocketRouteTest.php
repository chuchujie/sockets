<?php

// Created by dealloc. All rights reserved.

use Experus\Sockets\Core\Routing\SocketRoute;
use Experus\Sockets\Core\Server\SocketRequest;
use Illuminate\Contracts\Foundation\Application;
use Mockery as m;

/**
 * Class SocketRouteTest tests the \Experus\Sockets\Core\Routing\SocketRoute class.
 */
class SocketRouteTest extends TestCase
{
    /**
     * A dummy URL for mocking routes.
     *
     * @var string
     */
    const FOO_URL = 'foo';

    /**
     * A dummy URL for mocking routes that need to differ from FOO_URL
     *
     * @var string
     */
    const BAR_URL = 'bar';

    /**
     * A route name that is guaranteed to this test.
     *
     * @var string
     */
    const TEST_NAME = '__ROUTE_TEST__';

    /**
     * The laravel application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The mocked request we're testing with.
     *
     * @var \Mockery\MockInterface
     */
    protected $request;

    /**
     * Set up the testing environment.
     */
    public function setUp()
    {
        $this->app = m::mock(Application::class);

        $this->request = m::mock(SocketRequest::class);
    }

    /**
     * Test if we can pass an empty array as attributes to a route.
     *
     * @test
     */
    public function emptyAttributes()
    {
        $route = new SocketRoute(self::FOO_URL, [], '', [], $this->app);

        self::assertEquals($route->path(), self::FOO_URL,
            'Route should have path ' . self::FOO_URL . '(has ' . $route->path() . ')');
    }

    /**
     * Test if we can pass a name attribute to a route.
     *
     * @test
     */
    public function nameAttribute()
    {
        $route = new SocketRoute(self::FOO_URL, [], '', ['name' => self::TEST_NAME], $this->app);

        self::assertEquals($route->name(), self::TEST_NAME);
    }

    /**
     * Test if the route falls back to it's URI when no name is supplied.
     *
     * @test
     */
    public function nameWithoutNameAttribute()
    {
        $route = new SocketRoute(self::FOO_URL, [], '', [], $this->app);

        self::assertEquals($route->name(), self::FOO_URL, 'Route should use path as name when no name is given');
    }

    /**
     * Test if a request to foo differs from a request to bar.
     *
     * @test
     */
    public function matchFooWithBar()
    {
        $foo = new SocketRoute(self::FOO_URL, [], '', [], $this->app);
        $this->request->shouldReceive('path')
            ->andReturn(self::BAR_URL)
            ->once();

        self::assertFalse($foo->match($this->request));
    }

    /**
     * Test if a request to foo matches with a request from foo.
     *
     * @test
     */
    public function matchFooWithFoo()
    {
        $foo = new SocketRoute(self::FOO_URL, [], '', [], $this->app);
        $this->request->shouldReceive('path')
            ->andReturn(self::FOO_URL)
            ->once();

        self::assertTrue($foo->match($this->request));
    }
}

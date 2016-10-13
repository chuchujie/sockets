<?php

// Created by dealloc. All rights reserved.

use Experus\Sockets\Core\Routing\SocketRoute;
use Experus\Sockets\Core\Server\SocketRequest;
use Illuminate\Contracts\Foundation\Application;
use Mockery as m;

class SocketRouteTest extends TestCase
{
    const FOO_URL = 'foo';
    const BAR_URL = 'bar';
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
	 * @test
	 */
	public function emptyAttributes()
    {
        $route = new SocketRoute(self::FOO_URL, [], '', [], $this->app);

        self::assertEquals($route->path(), self::FOO_URL, 'Route should have path ' . self::FOO_URL . '(has ' . $route->path() . ')');
    }

    /**
	 * @test
	 */
	public function nameAttribute()
    {
        $route = new SocketRoute(self::FOO_URL, [], '', ['name' => self::TEST_NAME], $this->app);

        self::assertEquals($route->name(), self::TEST_NAME);
    }

    /**
	 * @test
	 */
	public function nameWithoutNameAttribute()
    {
        $route = new SocketRoute(self::FOO_URL, [], '', [], $this->app);

        self::assertEquals($route->name(), self::FOO_URL, 'Route should use path as name when no name is given');
    }

    /**
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

<?php

// Created by dealloc. All rights reserved.

use Experus\Sockets\Core\Routing\SocketRouter;
use Experus\Sockets\Core\Server\SocketRequest;
use Experus\Sockets\Exceptions\NotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Mockery as m;

/**
 * Class SocketRouterTest tests the \Experus\Sockets\Core\Routing\SocketRouter class.
 */
class SocketRouterTest extends TestCase
{
    /**
     * A dummy URL for mocking routes
     *
     * @var string
     */
    const DUMMY_URL = '/foo';

    /**
     * The router we're testing on.
     *
     * @var SocketRouter|m\MockInterface
     */
    protected $router;

    /**
     * The mocked laravel application instance.
     *
     * @var m\MockInterface
     */
    protected $app;

    /**
     * Set up the testing environment.
     */
    public function setUp()
    {
        $this->app = m::mock(Application::class);
        $this->router = m::mock(new SocketRouter($this->app));
    }

    /**
     * Test if we can register a route with an array style controller action.
     *
     * @test
     */
    public function registerRouteWithControllerAction()
    {
        $this->router->socket(self::DUMMY_URL, ['uses' => 'FooController@foo']);
    }

    /**
     * Test if we can register a route with a string style controller action.
     *
     * @test
     */
    public function registerRouteWithString()
    {
        $this->router->socket(self::DUMMY_URL, 'FooController@foo');
    }

    /**
     * Test if we can register a route with a closure style action.
     *
     * @test
     */
    public function registerRouteWithClosure()
    {
        $this->router->socket(self::DUMMY_URL, function () {
            throw new RuntimeException('Route closure should not have been called');
        });
    }

    /**
     * Test if we can dispatch a route to an array style controller action.
     *
     * @test
     */
    public function dispatchToController()
    {
        $this->router->socket(self::DUMMY_URL, ['uses' => 'FooController@foo']);

        $this->app->shouldReceive('make')
            ->with('\FooController')
            ->andReturn(new FooController);

        $request = m::mock(SocketRequest::class)
            ->shouldReceive('path')
            ->andReturn(self::DUMMY_URL)
            ->once()->mock();

        $response = $this->router->dispatch($request);

        self::assertEquals($response, FooController::DUMMY_RESPONSE);
    }

    /**
     * Test if we can dispatch a route to a string style controller action.
     *
     * @test
     */
    public function dispatchToString()
    {
        $this->router->socket(self::DUMMY_URL, 'FooController@foo');

        $this->app->shouldReceive('make')
            ->with('\FooController')
            ->andReturn(new FooController);

        $request = m::mock(SocketRequest::class)
            ->shouldReceive('path')
            ->andReturn(self::DUMMY_URL)
            ->once()->mock();

        $response = $this->router->dispatch($request);

        self::assertEquals($response, FooController::DUMMY_RESPONSE);
    }

    /**
     * Test if we can dispatch a route to a closure style action.
     *
     * @test
     */
    public function dispatchToClosure()
    {
        $called = false;
        $this->router->socket(self::DUMMY_URL, function () use (&$called) {
            $called = true;
        });

        $request = m::mock(SocketRequest::class)
            ->shouldReceive('path')
            ->andReturn(self::DUMMY_URL)
            ->once()->mock(); // no clue why it's called twice though

        $this->router->dispatch($request);

        self::assertTrue($called);
    }

    /**
     * Test if we can dispatch without routes.
     * This should throw a NotFoundException.
     *
     * @test
     */
    public function dispatchWithoutRoutes()
    {
        $request = m::mock(SocketRequest::class)
            ->shouldReceive('path')
            ->andReturn(self::DUMMY_URL)
            ->once()->mock();

        $this->expectException(NotFoundException::class);
        $this->router->dispatch($request);
    }

    /**
     * Test if we can dispatch when routes are registered, but none match.
     * This should throw a NotFoundException.
     *
     * @test
     */
    public function dispatchWithoutMatchingRoutes()
    {
        $this->router->socket(self::DUMMY_URL, function () {
            throw new AssertionError('Route closure should not have been called');
        });

        $request = m::mock(SocketRequest::class)
            ->shouldReceive('path')
            ->andReturn(null)
            ->twice()->mock();

        $this->expectException(NotFoundException::class);
        $this->router->dispatch($request);
    }

    /**
     * Test if a group shares the namespace to it's sub routes.
     *
     * @test
     */
    public function groupWithNamespace()
    {
        $this->router->group(['namespace' => '\Mocks'], function ($router) {
            $router->socket(self::DUMMY_URL, 'BarController@bar');
        });

        $this->app->shouldReceive('make')
            ->with('\Mocks\BarController')
            ->andReturn(new \Mocks\BarController);

        $request = m::mock(SocketRequest::class)
            ->shouldReceive('path')
            ->andReturn(self::DUMMY_URL)
            ->once()->mock();

        $response = $this->router->dispatch($request);

        self::assertEquals($response, \Mocks\BarController::DUMMY_RESPONSE);
    }
}

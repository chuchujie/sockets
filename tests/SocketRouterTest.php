<?php

// Created by dealloc. All rights reserved.

use Experus\Sockets\Core\Routing\SocketRouter;
use Experus\Sockets\Core\Server\SocketRequest;
use Illuminate\Contracts\Foundation\Application;
use Mockery as m;

class SocketRouterTest extends TestCase
{
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

    public function setUp()
    {
        $this->app = m::mock(Application::class);
        $this->router = m::mock(new SocketRouter($this->app));
    }

    public function testRegisterRouteWithControllerAction()
    {
        $this->router->socket(self::DUMMY_URL, ['uses' => 'FooController@foo']);
    }

    public function testRegisterRouteWithString()
    {
        $this->router->socket(self::DUMMY_URL, 'FooController@foo');
    }

    public function testRegisterRouteWithClosure()
    {
        $this->router->socket(self::DUMMY_URL, function() {
            throw new RuntimeException('Route closure should not have been called');
        });
    }

    public function testDispatchToController()
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

    public function testDispatchToString()
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

    public function testDispatchToClosure()
    {
        $called = false;
        $this->router->socket(self::DUMMY_URL, function() use (&$called) {
            $called = true;
        });

        $request = m::mock(SocketRequest::class)
            ->shouldReceive('path')
            ->andReturn(self::DUMMY_URL)
            ->once()->mock(); // no clue why it's called twice though

        $this->router->dispatch($request);

        self::assertTrue($called);
    }

    public function testDispatchWithoutRoutes()
    {
        $request = m::mock(SocketRequest::class)
            ->shouldReceive('path')
            ->andReturn(self::DUMMY_URL)
            ->once()->mock();

        $this->expectException(RuntimeException::class);
        $this->router->dispatch($request);
    }

    public function testDispatchWithoutMatchingRoutes()
    {
        $this->router->socket(self::DUMMY_URL, function() {
            throw new AssertionError('Route closure should not have been called');
        });

        $request = m::mock(SocketRequest::class)
            ->shouldReceive('path')
            ->andReturn(null)
            ->twice()->mock();

        $this->expectException(RuntimeException::class);
        $this->router->dispatch($request);
    }

    /**
     * @todo implement when done with implementing channels
     */
    public function testChannel()
    {
    }

    public function testGroupWithNamespace()
    {
        $this->router->group(['namespace' => '\Mocks'], function($router) {
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

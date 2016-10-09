<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets;

use Experus\Sockets\Commands\ServeCommand;
use Experus\Sockets\Contracts\Routing\Router;
use Experus\Sockets\Contracts\Server\Server;
use Experus\Sockets\Core\Routing\SocketRouter;
use Experus\Sockets\Core\Server\SocketServer;
use Illuminate\Support\ServiceProvider;

/**
 * Class SocketProvider installs the contracts from the socket package and publishes the required config files.
 * @package Experus\Sockets
 */
class SocketProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    public $defer = true;

    /**
     * The bindings this service provider provides.
     *
     * @var array
     */
    private $bindings = [
        Server::class => SocketServer::class,
        Router::class => SocketRouter::class,
    ];

    /**
     * Register the services.
     */
    public function register()
    {
        foreach ($this->bindings as $contract => $binding) {
            $this->app->singleton($contract, $binding);
        }

        $this->commands([ ServeCommand::class ]);

        $this->registerMiddleware();
        $this->registerProtocols();
    }

    /**
     * Expose the configuration exports.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../files/socket' => base_path('socket'),
            __DIR__ . '/../files/routes.php' => base_path('routes/socket.php'),
        ], 'socket');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }

    /**
     * Register all middlewares
     */
    private function registerMiddleware()
    {
        if (property_exists($this, 'middlewares') && is_array($this->middlewares)) {
            foreach ($this->middlewares as $middleware) {
                // register middlewares
            }
        }
    }

    private function registerProtocols()
    {
        if (property_exists($this, 'protocols') && is_array($this->protocols)) {
            foreach ($this->protocols as $protocol) {
                // register protocols
            }
        }
    }
}
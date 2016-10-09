<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets;

use Experus\Sockets\Commands\ServeCommand;
use Experus\Sockets\Contracts\Routing\Router;
use Experus\Sockets\Core\Routing\SocketRouter;
use Experus\Sockets\Core\Server\SocketServer;
use Illuminate\Support\ServiceProvider;
use Ratchet\MessageComponentInterface;

/**
 * Class SocketProvider installs the contracts from the socket package and publishes the required config files.
 * @package Experus\Sockets
 */
class SocketProvider extends ServiceProvider
{
    /**
     * Register the services.
     */
    public function register()
    {
        $this->app->singleton(MessageComponentInterface::class, SocketServer::class);
        $this->app->singleton(Router::class, SocketRouter::class);

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
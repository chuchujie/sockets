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
}
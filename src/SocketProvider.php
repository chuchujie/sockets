<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets;

use Experus\Sockets\Commands\ServeCommand;
use Experus\Sockets\Core\Server\SocketServer;
use Illuminate\Support\ServiceProvider;
use Ratchet\MessageComponentInterface;

class SocketProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(MessageComponentInterface::class, SocketServer::class);

        $this->commands([ ServeCommand::class ]);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../socket' => base_path('socket'),
        ], 'server');
    }
}
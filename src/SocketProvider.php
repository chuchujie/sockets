<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets;

use Illuminate\Support\ServiceProvider;

class SocketProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../socket' => base_path('socket'),
        ], 'server');
    }
}
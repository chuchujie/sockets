<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class ServeCommand allows running the socket server from the Artisan runtime.
 * @package Experus\Sockets\Commands
 */
class ServeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:serve {--port= : The port on which the socket server should listen.} {--local : Wether the socket server should only listen to local connections.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a socket server';

    /**
     * The laravel application instance.
     *
     * @var Application
     */
    private $app;

    /**
     * Create a new command instance.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct();

        $this->app = $app;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->app->singleton(\Experus\Sockets\Contracts\Kernel::class,
            \Experus\Sockets\Core\SocketKernel::class
        );

        $kernel = $this->app->make(\Experus\Sockets\Contracts\Kernel::class);


        $kernel->init(
            $this->input,
            $this->output
        );

        $kernel->listen();
    }
}
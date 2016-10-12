<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Commands;

use Illuminate\Console\Command;

/**
 * Class SetupSocketsCommand is a one-command setup tool for sockets.
 * @package Experus\Sockets\Commands
 */
class SetupSocketsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup your development environment with one command!';

    /**
     * If the project is running with a subclass of SocketServiceProvider
     *
     * @var bool
     */
    private $customProvider = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->exists('app/Providers/SocketServiceProvider.php')) {
            $this->customProvider = 'app/Providers/SocketServiceProvider.php';
        }

        $this->setupVendor();
        $this->setupProvider();
        $this->setupControllers();
        $this->setupProtocols();
        $this->setupMiddlewares();
        $this->setupHandlers();
        $this->setupCatchers();

        dd($this->customProvider);
    }

    /**
     * Get the full path inside the laravel application.
     *
     * @param string $path
     * @return string
     */
    private function path($path = '')
    {
        return $this->getLaravel()->basePath() . '/' . $path;
    }

    /**
     * Check if the file or directory exists.
     *
     * @param string $path
     * @return bool
     */
    private function exists($path = '')
    {
        $path = $this->path($path);
        return is_dir($path) || is_file($path);
    }

    /**
     * Setup route and bootstrap files.
     */
    private function setupVendor()
    {
        if ($this->exists('socket') || !$this->confirm('Do you want to publish the required assets?', true)) {
            return;
        }

        $this->call('vendor:publish', ['--tag' => 'sockets']);
    }

    /**
     * Setup custom provider.
     */
    private function setupProvider()
    {
        if ($this->exists('app/Providers/SocketServiceProvider.php') || !$this->confirm('Do you want to generate a custom provider?', true)) {
            return;
        }

        $this->customProvider = 'app/Providers/SocketServiceProvider.php';

        $this->call('socket:provider', ['name' => 'SocketServiceProvider']);

        if ($this->exists('config/app.php') && $this->confirm('Do you want to swap the default SocketServiceProvider with your new provider?', true)) {
            $file = $this->getLaravel()->basePath() . '/config/app.php';
            $config = file_get_contents($file);
            $config = str_replace('\Experus\Sockets\SocketServiceProvider', '\App\Providers\SocketServiceProvider', $config);
            file_put_contents($file, $config);
        }
    }

    /**
     * Generate controller file
     */
    private function setupControllers()
    {
        if ($this->confirm('Do you want to generate a controller?', true)) {
            $name = $this->ask('How should we name the controller?');

            $this->call('socket:controller', compact('name'));
        }
    }

    /**
     * Generate protocol
     */
    private function setupProtocols()
    {
        if ($this->confirm('Do you want to generate a protocol?', true)) {
            $name = $this->ask('How should we name the protocol?');

            $this->call('socket:protocol', compact('name'));
        }
    }

    /**
     * Generate middleware
     */
    private function setupMiddlewares()
    {
        if ($this->confirm('Do you want to generate a middleware?', true)) {
            $name = $this->ask('How should we name the middleware?');

            $this->call('socket:middleware', compact('name'));
        }
    }

    /**
     * Generate exception handler
     */
    private function setupHandlers()
    {
        if ($this->confirm('Do you want to generate a handler?', true)) {
            $name = $this->ask('How should we name the handler?');

            $this->call('socket:handler', compact('name'));
        }
    }

    /**
     * Generate exception catcher.
     */
    private function setupCatchers()
    {
        if ($this->confirm('Do you want to generate a catcher?', true)) {
            $name = $this->ask('How should we name the catcher?');

            $this->call('socket:catcher', compact('name'));
        }
    }
}

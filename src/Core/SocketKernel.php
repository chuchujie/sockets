<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core;

use Experus\Sockets\Contracts\Kernel;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;

class SocketKernel implements Kernel
{
    /**
     * The bootstrap classes for the application.
     *
     * @var array
     */
    protected $bootstrappers = [
        'Illuminate\Foundation\Bootstrap\DetectEnvironment',
        'Illuminate\Foundation\Bootstrap\LoadConfiguration',
        'Illuminate\Foundation\Bootstrap\ConfigureLogging',
        'Illuminate\Foundation\Bootstrap\HandleExceptions',
        'Illuminate\Foundation\Bootstrap\RegisterFacades',
        'Illuminate\Foundation\Bootstrap\SetRequestForConsole',
        'Illuminate\Foundation\Bootstrap\RegisterProviders',
        'Illuminate\Foundation\Bootstrap\BootProviders',
    ];

    /**
     * The Laravel application.
     *
     * @var Application
     */
    private $app;

    /**
     * The Symfony input interface.
     *
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    /**
     * The Symfony output interface.
     *
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->bootstrap();
    }

    /**
     * Initialize the socket runtime.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return \Experus\Sockets\Contracts\Kernel
     */
    public function init(Input $input, Output $output)
    {
        $this->input = $input;
        $this->output = $output;

        return $this;
    }

    /**
     * Start the socket server and accept incoming connections.
     */
    public function listen()
    {
        $this->output->writeln('TODO listen to incoming socket connections and bind them into the laravel runtime.');
    }

    private function bootstrap()
    {
        if (!$this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers);
        }
    }
}
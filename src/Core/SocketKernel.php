<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core;

use Experus\Sockets\Contracts\Kernel;
use Experus\Sockets\Contracts\Routing\Router;
use Illuminate\Contracts\Foundation\Application;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
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

    /**
     * The running socket server.
     *
     * @var \Ratchet\MessageComponentInterface
     */
    private $server;

    /**
     * The socket router.
     *
     * @var Router
     */
    private $router;

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
        $this->server = $this->app->make(\Ratchet\MessageComponentInterface::class);
        $this->router = $this->app->make(Router::class);

        $this->initRouter();

        return $this;
    }

    /**
     * Start the socket server and accept incoming connections.
     */
    public function listen()
    {
        $this->output->writeln('Listening for incoming connections on port 9999');
        IoServer::factory(new HttpServer(new WsServer($this->server)), 9999)->run();
    }

    private function bootstrap()
    {
        if (!$this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers);
        }
    }

    private function initRouter()
    {
        $router = $this->router;

        require $this->app->basePath() . '/routes/socket.php';
    }
}
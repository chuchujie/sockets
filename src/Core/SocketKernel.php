<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core;

use Experus\Sockets\Contracts\Kernel;
use Experus\Sockets\Contracts\Routing\Router;
use Experus\Sockets\Contracts\Server\Server;
use Illuminate\Contracts\Foundation\Application;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;

/**
 * Class SocketKernel bootstraps the runtime and manages the socket server.
 * It exposes the socket as a separate runtime to the Laravel framework.
 *
 * @package Experus\Sockets\Core
 */
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

    /**
     * SocketKernel constructor.
     * @param Application $app
     */
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
        $this->server = $this->app->make(Server::class);
        $this->router = $this->app->make(Router::class);

        $this->initRouter();

        return $this;
    }

    /**
     * Start the socket server and accept incoming connections.
     */
    public function listen()
    {
        $this->output->writeln('Listening for incoming connections on ' . $this->getHost() . ':' . $this->getPort());
        IoServer::factory(new HttpServer(new WsServer($this->server)), $this->getPort(), $this->getHost())->run();
    }

    /**
     * Bootstrap the laravel runtime.
     */
    private function bootstrap()
    {
        if (!$this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers);
        }
    }

    /**
     * Initialize the router and load the routes.
     */
    private function initRouter()
    {
        $router = $this->router; // this variable is accessed in the routes file.

        require $this->app->basePath() . '/routes/socket.php';
    }

    /**
     * Get the port on which the socket server will listen for connections.
     *
     * @return int|string
     */
    private function getPort()
    {
        $option = ($this->input->hasOption('port') ? $this->input->getOption('port') : '');

        if (!empty($option)) {
            return $option;
        }

        return 9999;
    }

    /**
     * Get the host on which the socket server will listen for connections.
     *
     * @return string
     */
    private function getHost()
    {
        $option = $this->input->hasOption('local') && $this->input->getOption('local');

        if ($option) {
            return '127.0.0.1';
        }

        return '0.0.0.0';
    }
}
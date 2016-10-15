<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets;

use Experus\Sockets\Commands\GenerateCatcherCommand;
use Experus\Sockets\Commands\GenerateControllerCommand;
use Experus\Sockets\Commands\GenerateHandlerCommand;
use Experus\Sockets\Commands\GenerateMiddlewareCommand;
use Experus\Sockets\Commands\GenerateProtocolCommand;
use Experus\Sockets\Commands\GenerateProviderCommand;
use Experus\Sockets\Commands\ServeCommand;
use Experus\Sockets\Commands\SetupSocketsCommand;
use Experus\Sockets\Contracts\Exceptions\Handler;
use Experus\Sockets\Contracts\Middlewares\Stack;
use Experus\Sockets\Contracts\Routing\Router;
use Experus\Sockets\Contracts\Server\Server;
use Experus\Sockets\Core\Client\SocketClient;
use Experus\Sockets\Core\Exceptions\DebugHandler;
use Experus\Sockets\Core\Middlewares\SocketMiddlewareStack;
use Experus\Sockets\Core\Protocols\ExperusProtocol;
use Experus\Sockets\Core\Routing\SocketRouter;
use Experus\Sockets\Core\Server\SocketServer;
use Illuminate\Support\ServiceProvider;

/**
 * Class SocketProvider installs the contracts from the socket package and publishes the required config files.
 * @package Experus\Sockets
 */
class SocketServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    public $defer = true;

    /**
     * The supplied protocol stack.
     *
     * @var array
     */
    protected $protocols = [
        SocketClient::DEFAULT_PROTOCOL => ExperusProtocol::class,
    ];

    /**
     * The exception handler for handling exceptions occurring when processing Websockets.
     *
     * @var Handler
     */
    protected $handler = DebugHandler::class;

    /**
     * The global middleware stack for the sockets runtime.
     *
     * @var Stack
     */
    protected $stack = SocketMiddlewareStack::class;

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

        $this->commands([
            ServeCommand::class,
            GenerateControllerCommand::class,
            GenerateMiddlewareCommand::class,
            GenerateProviderCommand::class,
            GenerateHandlerCommand::class,
            GenerateCatcherCommand::class,
            GenerateProtocolCommand::class,
            SetupSocketsCommand::class,
        ]);

        $this->registerProtocols();
        $this->app->singleton(Handler::class, $this->handler);
        $this->app->singleton(Stack::class, $this->stack);
    }

    /**
     * Expose the configuration exports.
     * @todo remove base_path calls to remove hard dependency on Laravel framework.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../files/socket' => base_path('socket'),
            __DIR__ . '/../files/routes.php' => base_path('routes/socket.php'),
        ], 'sockets');
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
     * Register all protocols.
     */
    private function registerProtocols()
    {
        if (!empty($this->protocols)) {
            $server = $this->app->make(Server::class);
            foreach ($this->protocols as $name => $protocol) {
                $server->registerProtocol($name, $protocol);
            }
        }
    }
}
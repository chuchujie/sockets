<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Contracts\Exceptions;

use Exception;
use Experus\Sockets\Core\Client\SocketClient;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class Handler provides a base class for classes that handle socket exceptions.
 * @package Experus\Sockets\Contracts\Exceptions
 */
abstract class Handler
{
    /**
     * The registered exception handlers.
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * The laravel application.
     *
     * @var Application
     */
    private $app;

    /**
     * Handler constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Called when an exception does not have a registered handler.
     *
     * @param SocketClient $client
     * @param Exception $exception
     * @return array|null|object
     * @throws Exception if the exception thrown is fatal.
     * @note When throwing an exception in the handler, the entire server will halt.
     */
    public function unhandled(SocketClient $client, Exception $exception)
    {
    }

    /**
     * Dispatch the exception to the appropriate handler.
     *
     * @param SocketClient $client
     * @param Exception $exception
     * @return array|null|object
     */
    public function handle(SocketClient $client, Exception $exception)
    {
        $type = get_class($exception);
        foreach ($this->handlers as $handler => $handles) {
            $handles = is_array($handles) ? $handles : [$handles];

            if (in_array($type, $handles)) {
                return $this->app->make($handler)->handle($client, $exception);
            }
        }

        return $this->unhandled($client, $exception);
    }
}
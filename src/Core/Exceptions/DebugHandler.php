<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Exceptions;

use Exception;
use Experus\Sockets\Contracts\Exceptions\Handler;
use Experus\Sockets\Core\Client\SocketClient;

/**
 * Class DebugHandler throws the exception again, effectively crashing the server.
 * When running the server through artisan with -vvv this may be very handy for debugging.
 * <b>NEVER</b> use this handler in production.
 *
 * @package Experus\Sockets\Core\Exceptions
 */
class DebugHandler extends Handler
{
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
        throw $exception;
    }
}
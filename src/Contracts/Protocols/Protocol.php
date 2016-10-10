<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Contracts\Protocols;

use Experus\Sockets\Core\Server\SocketRequest;

/**
 * Interface Protocol is a contract describing how a message should be interpreted by the application.
 *
 * @package Experus\Sockets\Contracts\Protocols
 */
interface Protocol
{
    /**
     * Extract the intended route from the request.
     *
     * @param SocketRequest $request
     * @return string
     */
    public function route(SocketRequest $request);
}
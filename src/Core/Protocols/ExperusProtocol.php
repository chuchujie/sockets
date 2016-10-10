<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Protocols;

use Experus\Sockets\Contracts\Protocols\Protocol;
use Experus\Sockets\Core\Server\SocketRequest;

class ExperusProtocol implements Protocol
{
    /**
     * Extract the intended route from the request.
     *
     * @param SocketRequest $request
     * @return string
     */
    public function route(SocketRequest $request)
    {
        return '';
    }
}
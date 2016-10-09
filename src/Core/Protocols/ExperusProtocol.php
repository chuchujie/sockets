<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Protocols;

use Experus\Sockets\Contracts\Protocols\Protocol;
use Experus\Sockets\Core\Server\SocketRequest;

class ExperusProtocol implements Protocol
{
    /**
     * The request being handled by the protocol.
     *
     * @var SocketRequest
     */
    private $request;

    /**
     * ExperusProtocol constructor.
     * @param SocketRequest $request
     */
    public function __construct(SocketRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Extract the intended route from the request.
     *
     * @return string
     */
    public function route()
    {
        return '';
    }
}
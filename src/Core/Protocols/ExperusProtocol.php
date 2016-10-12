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
        return $request->parse()->to;
    }

    /**
     * Return the body of the request, stripping out all meta data such as route, auth, ...
     *
     * @return string
     */
    public function body(SocketRequest $request)
    {
        return $request->parse()->data;
    }

    /**
     * Return the parsed form of the request body as an array, an object or null if this protocol does not support parsing.
     *
     * @param SocketRequest $request
     * @return array|null|object
     */
    public function parse(SocketRequest $request)
    {
        return json_decode($request->raw());
    }
}
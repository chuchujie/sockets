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

    /**
     * Return the body of the request, stripping out all meta data such as route, auth, ...
     *
     * @return string
     */
    public function body(SocketRequest $request);

    /**
     * Return the parsed form of the request body as an array, an object or null if this protocol does not support parsing.
     *
     * @param SocketRequest $request
     * @return array|null|object
     * @todo check if parsing works and throw ParseException if not
     */
    public function parse(SocketRequest $request);
}
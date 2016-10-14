<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Contracts\Protocols;

use Experus\Sockets\Core\Server\SocketRequest;
use Experus\Sockets\Exceptions\ParseException;
use Experus\Sockets\Exceptions\SerializeException;

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
     * @param SocketRequest $request
     * @return string
     */
    public function body(SocketRequest $request);

    /**
     * Return the parsed form of the request body as an array, an object or null if this protocol does not support parsing.
     *
     * @param SocketRequest $request
     * @return array|null|object
     * @throws ParseException thrown when parsing fails.
     */
    public function parse(SocketRequest $request);

    /**
     * Serialize a response into the string representation that can be flushed into the sockets.
     *
     * @param array|null|object $data
     * @return string
     * @throws SerializeException when the given response cannot be serialized with this protocol.
     */
    public function serialize($data);
}
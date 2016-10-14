<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Protocols;

use Experus\Sockets\Contracts\Protocols\Protocol;
use Experus\Sockets\Core\Server\SocketRequest;
use Experus\Sockets\Exceptions\ParseException;
use Experus\Sockets\Exceptions\SerializeException;

/**
 * Class ExperusProtocol the default implementation of a protocol provided by sockets so you can play around with the framework.
 * @package Experus\Sockets\Core\Protocols
 */
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
     * @param SocketRequest $request
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
     * @throws ParseException thrown when parsing fails.
     */
    public function parse(SocketRequest $request)
    {
        $result = json_decode($request->raw());

        if (is_null($result)) {
            throw new ParseException($request->raw());
        }

        return $result;
    }

    /**
     * Serialize a response into the string representation that can be flushed into the sockets.
     *
     * @param array|null|object $data
     * @return string
     * @throws SerializeException when the given response cannot be serialized with this protocol.
     */
    public function serialize($data)
    {
        $serialized = json_encode($data);

        if ($serialized === false) {
            throw new SerializeException($data);
        }

        return $serialized;
    }
}
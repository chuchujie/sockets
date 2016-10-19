<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Exceptions;

use Exception;

/**
 * Class SerializeException thrown when the protocol fails to serialize the response to the client.
 * @package Experus\Sockets\Exceptions
 */
class SerializeException extends Exception
{
    /**
     * @var array|null|object
     */
    private $raw;

    /**
     * SerializeException constructor.
     * @param array|null|object $raw
     */
    public function __construct($raw)
    {
        parent::__construct('Cannot serialize instance of ' . get_class($raw));
        $this->raw = $raw;
    }

    /**
     * Get the response that failed to serialize.
     *
     * @return array|null|object
     */
    public function getResponse()
    {
        return $this->raw;
    }
}
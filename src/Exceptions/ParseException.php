<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Exceptions;

use Exception;

/**
 * Class ParseException is thrown when a protocol fails to parse a request.
 * @package Experus\Sockets\Exceptions
 */
class ParseException extends Exception
{
    /**
     * The payload that the protocol failed to parse.
     *
     * @var string
     */
    private $payload;

    /**
     * ParseException constructor.
     * @param string $payload
     */
    public function __construct($payload = '')
    {
        parent::__construct('Could not parse "' . $payload . '"');
        $this->payload = $payload;
    }

    /**
     * Get the payload the protocol failed to parse.
     *
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
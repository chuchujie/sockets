<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Exceptions;

use Exception;

/**
 * Class ProtocolNotFoundException thrown when attempting to resolve an unknown protocol.
 * @package Experus\Sockets\Exceptions
 */
class ProtocolNotFoundException extends Exception
{
    /**
     * The protocol that could not be resolved.
     *
     * @var string
     */
    private $protocol;

    /**
     * ProtocolNotFoundException constructor.
     * @param string $protocol
     */
    public function __construct($protocol)
    {
        parent::__construct('Could not resolve protocol "' . $protocol . '"!');
        $this->protocol = $protocol;
    }

    /**
     * Get the protocol that failed to resolve.
     *
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }
}
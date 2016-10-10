<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Exceptions;

use RuntimeException;

/**
 * Class NotFoundException is thrown when a request was made for which no action was registered.
 * @package Experus\Sockets\Exceptions
 */
class NotFoundException extends RuntimeException
{
    /**
     * NotFoundException constructor.
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct('No action found for request to ' . $path);
    }
}
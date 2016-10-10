<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Exceptions;

use RuntimeException;

class InvalidActionException extends RuntimeException
{
    public function __construct($name)
    {
        parent::__construct('Invalid action passed to route ' . $name);
    }
}
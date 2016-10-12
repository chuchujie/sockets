<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Exceptions;

use RuntimeException;

/**
 * Class InvalidActionException when an invalid route action was passed for a route.
 * @package Experus\Sockets\Exceptions
 */
class InvalidActionException extends RuntimeException
{
    /**
     * The name of the route that received an invalid action.
     *
     * @var string
     */
    private $name;

    /**
     * InvalidActionException constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct('Invalid action passed to route ' . $name);
        $this->name = $name;
    }

    /**
     * Get the name of the route.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
<?php
// Created by dealloc. All rights reserved.

namespace Mocks;

/**
 * Class BarController is a mock for a controller with a namespace.
 * @package Mocks
 */
class BarController
{
    /**
     * The response unique to this class.
     *
     * @var string
     */
    const DUMMY_RESPONSE = '__BAR_CONTROLLER__BAR__';

    /**
     * simple method that returns a unique but verifiable string.
     *
     * @return string
     */
    public function bar()
    {
        return self::DUMMY_RESPONSE;
    }
}
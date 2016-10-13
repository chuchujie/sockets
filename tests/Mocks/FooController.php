<?php
// Created by dealloc. All rights reserved.

/**
 * Class FooController is a mock for a controller without a namespace.
 */
class FooController
{
    /**
     * The response unique to this class.
     *
     * @var string
     */
    const DUMMY_RESPONSE = '__FOOCONTROLLER__FOO__RESPONSE__';

    /**
     * simple method that returns a unique but verifiable string.
     *
     * @return string
     */
    public function foo()
    {
        return self::DUMMY_RESPONSE;
    }
}
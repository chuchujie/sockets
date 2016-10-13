<?php
// Created by dealloc. All rights reserved.

use Mockery as m;

/**
 * Class TestCase is the sockets base test class.
 * Common testing logic can be shared here.
 */
class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        m::close();
    }
}
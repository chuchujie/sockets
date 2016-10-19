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

    /**
     * A slightly dirty hack.
     * we need this to bypass magic getter and setter problems caused by the decorator implementation of Ratchet.
     *
     * @param m\MockInterface $mock
     * @param string $name
     * @param mixed $value
     */
    protected function setMagicProperty(&$mock, $name, $value)
    {
        error_reporting(0); // disable the error reported by the magic setter
        $mock->$name = $value; // set the magic property
        error_reporting(E_ALL); // re-enable the error reporting
    }
}
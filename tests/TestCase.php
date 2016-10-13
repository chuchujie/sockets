<?php
// Created by dealloc. All rights reserved.

use Mockery as m;

class TestCase extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }
}
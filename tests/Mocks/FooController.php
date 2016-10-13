<?php
// Created by dealloc. All rights reserved.

class FooController
{
    const DUMMY_RESPONSE = '__FOOCONTROLLER__FOO__RESPONSE__';

    public function foo()
    {
        return self::DUMMY_RESPONSE;
    }
}
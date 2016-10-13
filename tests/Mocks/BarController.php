<?php
// Created by dealloc. All rights reserved.

namespace Mocks;

class BarController
{
    const DUMMY_RESPONSE = '__BAR_CONTROLLER__BAR__';

    public function bar()
    {
        return self::DUMMY_RESPONSE;
    }
}
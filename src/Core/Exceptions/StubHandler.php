<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Exceptions;

use Experus\Sockets\Contracts\Exceptions\Handler;

/**
 * Class StubHandler just passes exceptions back up the stack, effectively doing a noop with the exceptions.
 *
 * @package Experus\Sockets\Core\Exceptions
 */
class StubHandler extends Handler
{
}
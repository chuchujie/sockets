<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Core\Exceptions;

use Experus\Sockets\Contracts\Exceptions\Handler;

/**
 * Class NoopHandler does nothing with the exception, which triggers the default behaviour (send exception to client and close socket).
 *
 * @package Experus\Sockets\Core\Exceptions
 */
class NoopHandler extends Handler
{
}
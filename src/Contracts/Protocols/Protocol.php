<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Contracts\Protocols;

/**
 * Interface Protocol is a contract describing how a message should be interpreted by the application.
 * Implementations are passed the request instance in their constructor.
 *
 * @package Experus\Sockets\Contracts\Protocols
 */
interface Protocol
{
    /**
     * Extract the intended route from the request.
     *
     * @return string
     */
    public function route();
}
<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Contracts;

use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;

/**
 * Interface Kernel describes the contract for a Websocket server.
 *
 * @package Experus\Sockets\Contracts
 */
interface Kernel
{
    /**
     * Initialize the socket runtime.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return \Experus\Sockets\Contracts\Kernel
     */
    public function init(Input $input, Output $output);

    /**
     * Start the socket server and accept incoming connections.
     */
    public function listen();

    /**
     * Blacklist an address from the server.
     *
     * @param string $address
     */
    public function block($address);

    /**
     * Whitelist a host to connect to the server.
     *
     * @param string $address
     */
    public function allow($address);
}
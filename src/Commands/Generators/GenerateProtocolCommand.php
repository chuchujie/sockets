<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Commands\Generators;

/**
 * Class GenerateProtocolCommand generates socket protocols.
 * @package Experus\Sockets\Commands
 */
class GenerateProtocolCommand extends AbstractGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:protocol {name : The name of the protocol class} {--namespace= : The namespace of the protocol} {--dir= : The root directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sockets protocol class.';

    /**
     * The default namespace to generate into.
     *
     * @var string
     */
    protected $namespace = 'App\\Sockets\\Protocols';

    /**
     * The template file to generate from.
     *
     * @var string
     */
    protected $template = 'Protocol';
}

<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Commands\Generators;

/**
 * Class GenerateHandlerCommand generates exception handlers.
 * @package Experus\Sockets\Commands
 */
class GenerateHandlerCommand extends AbstractGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:handler {name : The name of the handler} {--namespace= : The namespace of the handler to generate} {--dir= : The root directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sockets handler class.';

    /**
     * The default namespace to generate into.
     *
     * @var string
     */
    protected $namespace = 'App\\Sockets\\Exceptions';

    /**
     * The template file to generate from.
     *
     * @var string
     */
    protected $template = 'Handler';
}
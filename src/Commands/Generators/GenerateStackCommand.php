<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Commands\Generators;


class GenerateStackCommand extends AbstractGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:stack {name : The name of the stack class} {--namespace= : The namespace of the stack} {--dir= : The root directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sockets stack class.';

    /**
     * The default namespace to generate into.
     *
     * @var string
     */
    protected $namespace = 'App\\Sockets\\Middleware';

    /**
     * The template file to generate from.
     *
     * @var string
     */
    protected $template = 'Stack';
}
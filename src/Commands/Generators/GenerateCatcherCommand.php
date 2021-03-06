<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Commands\Generators;

/**
 * Class GenerateCatcherCommand generates catchers.
 * @package Experus\Sockets\Commands
 */
class GenerateCatcherCommand extends AbstractGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:catcher {name : The name of the catcher class} {--namespace= : The namespace of the catcher} {--dir= : The root directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sockets catcher class.';

    /**
     * The default namespace to generate into.
     *
     * @var string
     */
    protected $namespace = 'App\\Sockets\\Exceptions\\Catchers';

    /**
     * The template file to generate from.
     *
     * @var string
     */
    protected $template = 'Catcher';
}
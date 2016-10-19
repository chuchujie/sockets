<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Commands\Generators;

use Experus\Sockets\Contracts\Kernel;

/**
 * Class GenerateControllerCommand generates controllers.
 * @package Experus\Sockets\Commands
 */
class GenerateControllerCommand extends AbstractGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:controller {name : The name of the controller} {--namespace= : The namespace of the controller to generate} {--dir= : The root directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sockets controller class.';

    /**
     * The default namespace to generate into.
     *
     * @var string
     */
    protected $namespace = Kernel::CONTROLLER_NAMESPACE;

    /**
     * The template file to generate from.
     *
     * @var string
     */
    protected $template = 'Controller';
}

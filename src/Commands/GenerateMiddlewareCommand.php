<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Commands;

/**
 * Class GenerateMiddlewareCommand generates middleware.
 * @package Experus\Sockets\Commands
 */
class GenerateMiddlewareCommand extends AbstractGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:middleware {name : The name of the middleware class} {--namespace= : The namespace of the middleware} {--dir= : The root directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sockets middleware class.';

    /**
     * The default namespace to generate into.
     *
     * @var string
     */
    protected $namespace = 'App\\Sockets\\Middlewares';

    /**
     * The template file to generate from.
     *
     * @var string
     */
    protected $template = 'Middleware';
}

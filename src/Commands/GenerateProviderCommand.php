<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Commands;


class GenerateProviderCommand extends AbstractGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:provider {name : The name of the provider class} {--namespace= : The namespace of the provider} {--dir= : The root directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sockets provider class.';

    /**
     * The default namespace to generate into.
     *
     * @var string
     */
    protected $namespace = 'App\\Providers';

    /**
     * The template file to generate from.
     *
     * @var string
     */
    protected $template = 'Provider';
}
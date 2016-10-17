<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Commands;

/**
 * Class GenerateProviderCommand generates SocketServiceProviders
 * @package Experus\Sockets\Commands
 */
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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        if ((is_dir('config/app.php') || is_file('config/app.php')) && $this->confirm('Do you want to swap the default SocketServiceProvider with your new provider?', true)) {
            $file = $this->getLaravel()->basePath() . '/config/app.php';
            $config = file_get_contents($file);
            $config = str_replace('\Experus\Sockets\SocketServiceProvider', '\App\Providers\\' . $this->argument('name'), $config);
            file_put_contents($file, $config);
        }
    }
}
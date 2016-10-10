<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Commands;

use Experus\Sockets\Contracts\Kernel;
use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;

class GenerateControllerCommand extends Command
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
     * @var Application
     */
    private $app;

    /**
     * Create a new command instance.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct();
        $this->app = $app;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $namespace = (is_null($this->option('namespace')) ? Kernel::CONTROLLER_NAMESPACE : $this->option('namespace'));
        $directory = $this->buildDirectory($namespace);
        $output = $directory . '/' . $name . '.php';

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $template = file_get_contents(__DIR__ . '/../../files/templates/Controller');
        $template = $this->compile($template, compact('name', 'namespace'));

        file_put_contents($output, $this->compile($template));
    }

    /**
     * Get the directory to generate the controller in.
     *
     * @param $namespace
     * @return string
     */
    private function buildDirectory($namespace)
    {
        $base = is_null($this->option('dir')) ? 'app' : $this->option('dir');

        $namespace = strstr($namespace, '\\');

        return $this->app->basePath() . '/' . $base . str_replace('\\', '/', $namespace);
    }

    /**
     * Compile a template file using the given environment variables.
     *
     * @param string $template
     * @param array $env
     * @return string
     */
    private function compile($template, array $env = [])
    {
        foreach ($env as $name => $value) {
            $template = str_replace('{{' . $name . '}}', $value, $template);
        }

        return preg_replace('/{{[a-zA-Z]+}}/', '', $template);
    }
}

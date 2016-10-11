<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;

abstract class AbstractGeneratorCommand extends Command
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * The default namespace to generate into.
     *
     * @var string
     */
    protected $namespace;

    /**
     * The template file to generate from.
     *
     * @var string
     */
    protected $template;

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
     */
    public function handle()
    {
        $name = $this->argument('name');
        $namespace = (is_null($this->option('namespace')) ? $this->namespace : $this->option('namespace'));
        $directory = $this->buildDirectory($namespace);
        $output = $directory . '/' . $name . '.php';

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $template = file_get_contents(__DIR__ . '/../../files/templates/' . $this->template);
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
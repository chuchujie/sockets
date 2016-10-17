<?php
// Created by dealloc. All rights reserved.

namespace Experus\Sockets\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class AbstractGeneratorCommand provides a base class for commands that generate classes from templates.
 * @package Experus\Sockets\Commands
 */
abstract class AbstractGeneratorCommand extends Command
{
    /**
     * The laravel application instance.
     *
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

        $this->prepareDirectory($directory);

        if (!$this->checkOverwrite($name, $output)) {
            return;
        }

        $template = file_get_contents(__DIR__ . '/../../files/templates/' . $this->template);
        $template = $this->compile($template, $this->env(compact('name', 'namespace')));

        file_put_contents($output, $this->compile($template));
        $this->output->success('Generated ' . $namespace . '\\' . $name);
    }

    /**
     * Build the environment variables for template parsing.
     *
     * @param array $defaults
     * @return array
     */
    protected function env(array $defaults)
    {
        return $defaults;
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

    /**
     * Prevent accidentally overwriting files by warning the user if the file exists.
     *
     * @param string $name
     * @param string $output
     * @return bool returns true if we can continue.
     */
    private function checkOverwrite($name, $output)
    {
        if (file_exists($output)) {
            $this->output->warning($name . ' already exists!');
            return (bool)$this->output->confirm('Do you want to overwrite?');
        }

        return true;
    }

    /**
     * Make sure the required directories exist or are created if they don't.
     *
     * @param string $directory
     */
    private function prepareDirectory($directory)
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }
}
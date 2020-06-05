<?php

namespace Laramab\Crudgenerator;

use Illuminate\Support\ServiceProvider;
use Laramab\Crudgenerator\Commands\CrudGeneratorCommand;

/**
 * Class CrudGeneratorServiceProvider
 * @package Laramab\Crudgenerator
 */
class CrudGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // register commands
        $this->commands([
            CrudGeneratorCommand::class,
        ]);

        // publish files
        $this->publishes([
            __DIR__ . '/config/crud-generator.php' => config_path('crud-generator.php')
        ], 'crud-generator');
    }
}

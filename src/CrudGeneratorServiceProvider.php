<?php

namespace Laramab\Crudgenerator;

use Illuminate\Support\ServiceProvider;

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
        $this->publishes([
            __DIR__ . '/CrudGeneratorCommand.php' => base_path('app/Console/Commands/CrudGeneratorCommand.php'),
            __DIR__ . '/stubs' => base_path('resources/stubs'),
            __DIR__ . '/crud-generator.php' => config_path('crud-generator.php')
        ]);
    }
}

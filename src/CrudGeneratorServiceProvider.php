<?php

namespace Laramab\Crudgenerator;

//use Illuminate\Support\Facades\File;
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
        /*if (file_exists(base_path('app/Console/Commands/CrudGeneratorCommand.php'))) {
            File::delete(base_path('app/Console/Commands/CrudGeneratorCommand.php'));
        }
        if (file_exists(base_path('config/crud-generator.php'))) {
            File::delete(base_path('config/crud-generator.php'));
        }
        File::deleteDirectories(base_path('resources/stubs'));*/

        $this->publishes([
            __DIR__ . '/CrudGeneratorCommand.php' => base_path('app/Console/Commands/CrudGeneratorCommand.php'),
            __DIR__ . '/stubs' => base_path('resources/stubs'),
            __DIR__ . '/crud-generator.php' => config_path('crud-generator.php')
        ]);
    }
}

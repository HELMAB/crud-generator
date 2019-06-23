<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generator
    {name : Class (singular) for example User}
    {columns : Class (singular) for example User}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD operations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $columns = $this->argument('columns');

        $this->migration($name, $columns);
        $this->model($name, $columns);
        $this->controller($name);
        $this->request($name);
        $this->route($name);
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    protected function migration($name, $columns)
    {
        $fields = explode(", ", $columns);
        $planText = '';
        foreach ($fields as $field) {
            $column = explode(':', $field);
            $planText .= '$table->' . $column[1] . '("' . $column[0] . '");' . PHP_EOL;
        }

        $controllerTemplate = str_replace(
            [
                '{{modelNamePlural}}',
                '{{tableName}}',
                '{{columns}}'
            ],
            [
                $name,
                strtolower(str_plural($name)),
                $planText
            ],
            $this->getStub('Migration')
        );
        $now = Carbon::now()->format('Y_m_d_hms');
        $name = $now . "_" . strtolower(str_plural($name)) . "_table";
        file_put_contents(app_path("../database/migrations/{$name}.php"), $controllerTemplate);
    }

    protected function model($name, $columns)
    {
        $fields = explode(", ", $columns);
        $planText = '';
        foreach ($fields as $field) {
            $column = explode(':', $field);
            $planText .= '"' . $column[0] . '",' . PHP_EOL;
        }

        $modelTemplate = str_replace(
            [
                '{{modelName}}',
                '{{columns}}'
            ],
            [
                $name,
                $planText
            ],
            $this->getStub('Model')
        );

        file_put_contents(app_path("/Models/{$name}.php"), $modelTemplate);
    }

    protected function controller($name)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}'
            ],
            [
                $name,
                strtolower(str_plural($name)),
                strtolower($name)
            ],
            $this->getStub('Controller')
        );

        file_put_contents(app_path("/Http/Controllers/{$name}Controller.php"), $controllerTemplate);
    }

    protected function route($name)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}'
            ],
            [
                strtolower(str_plural($name)),
                $name,
                $name
            ],
            $this->getStub('Route')
        );
        file_put_contents(app_path("../routes/Backend/{$name}.php"), $controllerTemplate);
    }

    protected function request($name)
    {
        $requestTemplate = str_replace(
            ['{{modelName}}'],
            [$name],
            $this->getStub('Request')
        );

        if (!file_exists($path = app_path('/Http/Requests')))
            mkdir($path, 0777, true);

        file_put_contents(app_path("/Http/Requests/{$name}Request.php"), $requestTemplate);
    }
}

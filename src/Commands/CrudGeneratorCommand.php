<?php

namespace Laramab\Crudgenerator\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Class CrudGeneratorCommand
 * @package App\Console\Commands
 */
class CrudGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generator
    {name : Class (singular) for example User}
    {name_km : Give name in Khmer Language (singular) for example: អត្តបទ}
    {name_en : Give name in English Language (singular) for example: article}
    {columns : "title:string, body:text, is_activated:boolean, published_at:dateTime"}';

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
        $name_km = $this->argument('name_km');
        $name_en = $this->argument('name_en');
        $columns = $this->argument('columns');

        $this->createDicIfNotExist();
        $this->migration($name, $columns);
        $this->model($name, $columns);
        $this->controller($name, $name_km, $name_en);
        $this->request($name);
        $this->route($name);
        $this->info("The $name model was generated successfully");
    }

    protected function createDicIfNotExist()
    {
        $pathModel = config('crud-generator.path_model', 'app/Models/');
        $pathController = config('crud-generator.path_controller', 'app/Http/Controllers/Api/Backend/');
        $pathRoute = config('crud-generator.path_route', 'routes/Api/Backend/');
        $pathRequest = config('crud-generator.path_request', 'app/Http/Requests/');

        if (!File::isDirectory($pathModel)) {
            File::makeDirectory($pathModel, 0777, true, true);
        }
        if (!File::isDirectory($pathController)) {
            File::makeDirectory($pathController, 0777, true, true);
        }
        if (!File::isDirectory($pathRoute)) {
            File::makeDirectory($pathRoute, 0777, true, true);
        }
        if (!File::isDirectory($pathRequest)) {
            File::makeDirectory($pathRequest, 0777, true, true);
        }
    }

    protected function getStub($type)
    {
        return file_get_contents(__DIR__ . "/../stubs/$type.stub");
    }

    protected function migration($name, $columns)
    {
        $fields = explode(", ", $columns);
        $planText = '';
        foreach ($fields as $index => $field) {
            $column = explode(':', $field);
            if ($index == 0) {
                $planText .= '$table->' . $column[1] . '("' . $column[0] . '");' . "\n";
            } elseif ($index == count($fields) - 1) {
                $planText .= "\t\t\t" . '$table->' . $column[1] . '("' . $column[0] . '");';
            } else {
                $planText .= "\t\t\t" . '$table->' . $column[1] . '("' . $column[0] . '");' . "\n";
            }
        }

        $tableName = Str::lower(Str::plural(Str::snake($name)));

        $controllerTemplate = str_replace(
            [
                '{{modelNamePlural}}',
                '{{tableName}}',
                '{{columns}}'
            ],
            [
                Str::plural($name),
                $tableName,
                $planText
            ],
            $this->getStub('Migration')
        );
        $now = Carbon::now()->format('Y_m_d_His');
        $name = $now . "_create_" . $tableName . "_table";
        file_put_contents(app_path("../database/migrations/{$name}.php"), $controllerTemplate);
        $this->info("database/migrations/{$name}.php");
    }

    protected function model($name, $columns)
    {
        $fields = explode(", ", $columns);
        $planText = '';
        foreach ($fields as $index => $field) {
            $column = explode(':', $field);
            if ($index == 0) {
                $planText .= "'$column[0]'," . "\n";
            } elseif ($index == count($fields) - 1) {
                $planText .= "\t\t" . "'$column[0]',";
            } else {
                $planText .= "\t\t" . "'$column[0]'," . "\n";
            }
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

        file_put_contents(base_path(config('crud-generator.path_model') . "/{$name}.php"), $modelTemplate);
        $this->info(config('crud-generator.path_model') . "/{$name}.php");
    }

    protected function controller($name, $name_km = null, $name_en = null)
    {
        $responseSuccessFormat = config('crud-generator.response_success_format', 'message_success');
        $responseErrorFormat = config('crud-generator.response_error_format', 'message_error');

        $controllerNamespace = config('crud-generator.path_controller');
        $controllerNamespace = str_replace("/", "\'", $controllerNamespace);
        $controllerNamespace = str_replace("app", "App", $controllerNamespace);
        $controllerNamespace = str_replace("'", "", $controllerNamespace);

        $paramUuid = str_replace("-", "_", Str::singular(Str::kebab($name)));

        $controllerTemplate = str_replace(
            [
                '{{controllerNamespace}}',
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelLabelKm}}',
                '{{modelLabelEn}}',
                '{{responseSuccessFormat}}',
                '{{responseErrorFormat}}',
                '{{paramUuid}}',
            ],
            [
                $controllerNamespace,
                $name,
                Str::plural(Str::camel($name)),
                Str::camel($name),
                $name_km,
                $name_en,
                $responseSuccessFormat,
                $responseErrorFormat,
                $paramUuid,
            ],
            $this->getStub('Controller')
        );

        file_put_contents(base_path(config('crud-generator.path_controller') . "/{$name}Controller.php"), $controllerTemplate);
        $this->info(config('crud-generator.path_controller') . "/{$name}Controller.php");
    }

    protected function route($name)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}'
            ],
            [
                $name,
                Str::kebab($name)
            ],
            $this->getStub('Route')
        );
        file_put_contents(base_path(config('crud-generator.path_route') . "/{$name}.php"), $controllerTemplate);
        $this->info(config('crud-generator.path_route') . "/{$name}.php");
    }

    protected function request($name)
    {
        $requestTemplate = str_replace(
            ['{{modelName}}'],
            [$name],
            $this->getStub('Request')
        );

        file_put_contents(base_path(config('crud-generator.path_request') . "/{$name}Request.php"), $requestTemplate);
        $this->info(config('crud-generator.path_request') . "/{$name}Request.php");
    }
}

# Laravel CRUD Generator


## Installation
```php
composer required laramab/crudgenerator
```

Add crud generator service provider

```php
Laramab\Crudgenerator\CrudGeneratorServiceProvider::class,
```

Published configuration

````php
php artisan vendor:pushlish --provider="Laramab\Crudgenerator\CrudGeneratorServiceProvider"
````
it will generate ```crud-generator.php``` configuration file which is allow you can custom your own route, model, migration and controller directory

```php
<?php

/**
 * @package laramab/crudgenerator
 *
 * @author Hel Mab
 * @date 2019/06/23
 */
return [
    /*
    |--------------------------------------------------------------------------
    | Model directory
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    'path_model' => 'app/Models',
    /*
    |--------------------------------------------------------------------------
    | Controller directory
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    'path_controller' => 'app/Http/Controllers/Frontend',
    /*
    |--------------------------------------------------------------------------
    | Route directory
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    'path_route' => 'routes/Backend',
    /*
    |--------------------------------------------------------------------------
    | Request directory
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    'path_request' => 'app/Http/Requests',
];
```

## Usage

```php
php artisan crud:generator Post "title:string, body:text, is_active:boolean, published_at:dateTime"
```

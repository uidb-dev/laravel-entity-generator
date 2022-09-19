# Laravel Entity Generator - SOA

========

**Laravel Entity Generator**

Laravel 5.5 repository design pattern generator with SOA(Service Oriented Arcitecture) inspired from this blog post: http://dfg.gd/blog/decoupling-your-code-in-laravel-using-repositiories-and-services.

## Installation

```php
composer require ybaruchel/laravel-entity-generator
```

## Usage
```
php artisan make:entity Example
```

It will generate the following structure by default configuration:

```
app
└── Repositories
    ├── Example
    |  ├── ExampleRepository.php
    |  ├── ExampleRepositoryServiceProvider.php
    |  └── ExampleInterface.php
    Services
    ├── Example
    |   ├── ExampleFacade.php
    |   ├── ExampleService.php
    |   └── ExampleServiceServiceProvider.php
    Models
    ├── Entities
    |   └── Example.php
    
```

Then add the service providers to the providers array in config/app.php :

```php
'providers' => [

    App\Repositories\Example\ExampleRepositoryServiceProvider::class,
    App\Services\Example\ExampleServiceServiceProvider::class,
```

If you want to use the facade, add this to your facades in app.php:

```php
'aliases' => [

    'ExampleFacade' => App\Services\Example\ExampleFacade::class,

```
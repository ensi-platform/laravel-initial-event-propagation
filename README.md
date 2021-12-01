# Laravel Initial Evenet Propagation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ensi/laravel-initial-event-propagation.svg?style=flat-square)](https://packagist.org/packages/ensi/laravel-initial-event-propagation)
[![Tests](https://github.com/ensi-platform/laravel-initial-event-propagation/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/ensi-platform/laravel-initial-event-propagation/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ensi/laravel-initial-event-propagation.svg?style=flat-square)](https://packagist.org/packages/ensi/laravel-initial-event-propagation)

Provides a bunch of ready-to use middleware to integrate [ensi/initial-event-propagation](https://github.com/ensi-platform/php-initial-event-propagation/) in Laravel application.
You are free to replace any of them with your own implementations.

## Installation

You can install the package via composer:

`composer require ensi/laravel-initial-event-propagation`

Publish config file like this:

`php artisan vendor:publish --provider="Ensi\LaravelInitialEventPropagation\LaravelInitialEventPropagationServiceProvider"`

## Basic Usage

```php
$holder = resolve(InitialEventHolder::class);
var_dump($holder->getInitialEvent());
$holder->setInitialEvent(new InitialEventDTO(...));
```

You must always resolve InitialEventHolder from the service container instead of `InitialEventHolder::getInstance`.
This is made forLaravel Octane compatibility.

### HTTP Requests

#### Setting initial event

You typically create a new initial event when you receive a HTTP request coming from a client you do not own. E.g in an API Gateway.  
There is a built-in `Ensi\LaravelInitialEventPropagation\SetInitialEventHttpMiddleware` for that.  
It creates an `InitialEventDTO` and places it to the `InitialEventHolder` singleton.  
- `userId` and `entrypoint` are set from request.
- `app` is set according to config options.
- `userType` is set from the package config. `userType` is empty for a not authenticated user.
- `correlationId` and `timestamp` are set from request headers according to config options or generated from scratch.
- `realUserId`, `realUserType` and `misc` are left empty strings.

Be sure to add the midlleware AFTER Laravel middleware that sets authenticated user.   
In practice it likely means that you have to place the middleare at the very bottom of `middlewareGroups` in `app/Http/Kernel`

#### Parsing incoming initial event

Add `Ensi\LaravelInitialEventPropagation\ParseInitialEventHeaderMiddleware` to `app/Http/Kernel` middleware property.  
This middleware parses `X-Initial-Event` HTTP header, deserializes it into `InitialEventDTO` object and places it to the `InitialEventHolder` singleton.

#### Propagating initial event to outcomming HTTP request
The package provides a `Ensi\LaravelInitialEventPropagation\PropagateInitialEventLaravelGuzzleMiddleware` Guzzle Middleware that converts ` resolve(InitialEventHolder::class)->getInitialEvent()` back to `X-Initial-Event` header and sets this header for all outcomming guzzle request.  

You can add it to your guzzle stack like this:

```php
$handlerStack = new HandlerStack(Utils::chooseHandler());
$handlerStack->push(new PropagateInitialEventLaravelGuzzleMiddleware());
```

### CLI

#### Artisan Commands

There is a custom artisan `Ensi\LaravelInitialEventPropagation\SetInitialEventArtisanMiddleware` that sets new initial event in every artisan command that you run.
You can add it to the `app\Console\Kernel` like that:

```php
public function bootstrap()
{
    parent::bootstrap();
    (new SetInitialEventArtisanMiddleware())->handle();
}
```
This middleware sets artisan command name (including argument, excluding options) as `$initialEventDTO->entrypoint`.  
If your custom artisan command makes guzzle HTTP requests to other apps the `PropagateInitialEventGuzzleMiddleware` uses this initial event.  
This middleware also works fine for [Laravel Task Scheduling](https://laravel.com/docs/latest/scheduling).

#### Queue Jobs

You typically want to persist initial event between incoming HTTP request and queued job.  
The package can help you here aswell. Unfortunately you need to touch a given job:

```php
use Ensi\LaravelInitialEventPropagation\Job;

// Extend the job from package
class TestJob extends Job implements ShouldQueue 
{
    public function __construct(protected Customer $customer)
    {
        // Do not forget to call parent constuctor
        parent::__construct();
    }

    public function handle()
    {
        // InitialEvent is automatically persisted to InitialEventHolder via job middleware in parent class, 
        // You do not need to persist it manually
    }
}
```

#### Laravel Queable Actions

If you use [spatie/laravel-queueable-action](https://github.com/spatie/laravel-queueable-action) package to dispatch actions instead of jobs you do not need to mess with every job separately.

Just publish `laravel-queueable-action` config and set the special Job class there:

```php 
'job_class' => \Ensi\LaravelInitialEventPropagation\ActionJob::class,
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

### Testing

1. composer install
2. npm i
3. composer test

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.



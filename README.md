# Laravel Inititator Propagation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ensi/laravel-initiator-propagation.svg?style=flat-square)](https://packagist.org/packages/ensi/laravel-initiator-propagation)
[![Tests](https://github.com/ensi-platform/laravel-initiator-propagation/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/ensi-platform/laravel-initiator-propagation/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ensi/laravel-initiator-propagation.svg?style=flat-square)](https://packagist.org/packages/ensi/laravel-initiator-propagation)

Provides a bunch of ready-to use middleware to integrate [ensi/initiator-propagation](https://github.com/ensi-platform/php-initiator-propagation/) in Laravel application.
You are free to replace any of them with your own implementations.

## Installation

You can install the package via composer:

`composer require ensi/laravel-initiator-propagation`

Publish config file like this:

`php artisan vendor:publish --provider="Ensi\LaravelInitiatorPropagation\LaravelInitiatorPropagationServiceProvider"`

## Basic Usage

```php
$holder = resolve(InitiatorHolder::class);
var_dump($holder->getInitiator());
$holder->setInitiator(new InitiatorDTO(...));
```

### HTTP Requests

#### Setting initiator

You typically create a new initiator when you receive a HTTP request coming from a client you do not own. E.g in an API Gateway.
There is a built-in `Ensi\LaravelInitiatorPropagation\SetInitiatorHttpMiddleware` for that.
It creates an `InitiatorDTO` and places it to the `InitiatorHolder` singleton.
- `userId` and `entrypoint` are set from request.
- `app` is set according to config options.
- `userType` is set from the package config. `userType` is empty for a not authenticated user.
- `correlationId` and `startedAt` are set from request headers according to config options or generated from scratch.
- `realUserId` and `realUserType` are left empty strings.

Be sure to add the midlleware AFTER Laravel middleware that sets authenticated user. 
In practice it likely means that you have to place the middleare at the very bottom of `middlewareGroups` in `app/Http/Kernel`

#### Parsing incoming initiator

Add `Ensi\LaravelInitiatorPropagation\ParseInitiatorHeaderMiddleware` to `app/Http/Kernel` middleware property.
This middleware parses `X-Initiator` HTTP header, deserializes it into `InitiatorDTO` object and places it to the `InitiatorHolder` singleton.

#### Propagating initiator to outcomming HTTP request
The package provides a `Ensi\InitiatorPropagation\PropagateInitiatorGuzzleMiddleware` Guzzle Middleware that converts ` resolve(InitiatorHolder::class)->getInitiator()` back to `X-Inititator` header and sets this header for all outcomming guzzle request.

You can add it to your guzzle stack like this:

```php
$handlerStack = new HandlerStack(Utils::chooseHandler());
$handlerStack->push(new PropagateInitiatorGuzzleMiddleware());
```

### CLI

#### Artisan Commands

There is a custom artisan `Ensi\LaravelInitiatorPropagation\SetInitiatorArtisanMiddleware` that sets new initiator in every artisan command that you run.
You can add it to the `app\Console\Kernel` like that:

```php
public function bootstrap()
{
    parent::bootstrap();
    (new SetInitiatorArtisanMiddleware())->handle();
}
```
This middleware sets artisan command name (including argument, excluding options) as `$initiatorDTO->entrypoint`.
If your custom artisan command makes guzzle HTTP requests to other apps the `PropagateInitiatorGuzzleMiddleware` uses this initiator.
This middleware also works fine for [Laravel Task Scheduling](https://laravel.com/docs/latest/scheduling).

#### Queue Jobs

You typically want to persist initiator between incoming HTTP request and queued job.
The package can help you here aswell. Unfortunately you need to touch a given job:

```php
use Ensi\LaravelInitiatorPropagation\Job;

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
        // Initiator is automatically persisted to InitiatorHolder via job middleware in parent class, 
        // You do not need to persist it manually
    }
}
```

#### Laravel Queable Actions

If you use [spatie/laravel-queueable-action](https://github.com/spatie/laravel-queueable-action) package to dispatch actions instead of jobs you do not need to mess with every job separately.

Just publish `laravel-queueable-action` config and set the special Job class there:

```php 
'job_class' => \Ensi\LaravelInitiatorPropagation\ActionJob::class,
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



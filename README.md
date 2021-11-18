# Laravel Inititator Propagation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ensi/laravel-initiator-propagation.svg?style=flat-square)](https://packagist.org/packages/ensi/laravel-initiator-propagation)
[![Tests](https://github.com/ensi-platform/laravel-initiator-propagation/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/ensi-platform/laravel-initiator-propagation/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ensi/laravel-initiator-propagation.svg?style=flat-square)](https://packagist.org/packages/ensi/laravel-initiator-propagation)

Generates Laravel application code from Open Api Specification files

## Installation

You can install the package via composer:

`composer require ensi/laravel-initiator-propagation`

You can publish config file like this:

`php artisan vendor:publish --provider="Ensi\LaravelInitiatorPropagation\LaravelInitiatorPropagationServiceProvider"`

## Basic Usage

## Setting initiator

You can use build-in `Ensi\InitiatorPropagation\ParseInitiatorHeaderLaravelMiddleware` to populate `InitiatorHolder` with data from incoming request.
It's also recommended to add `$this->app->instance(InitiatorHolder::class, InitiatorHolder::getInstance());` to one of your service providers to make `InitiatorHolder` singleton injectable via Laravel Service Container

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



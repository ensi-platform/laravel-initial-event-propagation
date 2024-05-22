<?php

namespace Ensi\LaravelInitialEventPropagation\Tests;

use Ensi\LaravelInitialEventPropagation\LaravelInitialEventPropagationServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelInitialEventPropagationServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}

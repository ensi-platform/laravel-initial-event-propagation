<?php

namespace Ensi\LaravelInitialEventPropagation\Tests;

use Ensi\LaravelInitialEventPropagation\LaravelInitialEventPropagationServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelInitialEventPropagationServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}

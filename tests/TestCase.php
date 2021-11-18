<?php

namespace Ensi\LaravelInitiatorPropagation\Tests;

use Ensi\LaravelInitiatorPropagation\LaravelInitiatorPropagationServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelInitiatorPropagationServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}

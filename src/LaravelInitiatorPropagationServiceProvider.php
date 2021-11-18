<?php

namespace Ensi\LaravelInitiatorPropagation;

use Ensi\InitiatorPropagation\InitiatorHolder;
use Illuminate\Support\ServiceProvider;

class LaravelInitiatorPropagationServiceProvider extends ServiceProvider
{
    const CONFIG_FILE_NAME = 'initiator-propagation.php';

    /**
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/' . self::CONFIG_FILE_NAME,
            'initiator-propagation'
        );

        $this->app->singleton(InitiatorHolder::class, fn () => new InitiatorHolder());
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/' . self::CONFIG_FILE_NAME => config_path(self::CONFIG_FILE_NAME),
        ]);
    }
}

<?php

namespace Ensi\LaravelInitialEventPropagation;

use Ensi\InitialEventPropagation\InitialEventHolder;
use Illuminate\Support\ServiceProvider;

class LaravelInitialEventPropagationServiceProvider extends ServiceProvider
{
    const CONFIG_FILE_NAME = 'initial-event-propagation.php';

    /**
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/' . self::CONFIG_FILE_NAME,
            'initial-event-propagation'
        );

        $this->app->singleton(InitialEventHolder::class, fn () => new InitialEventHolder());
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

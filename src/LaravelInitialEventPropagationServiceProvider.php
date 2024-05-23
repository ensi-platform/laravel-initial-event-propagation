<?php

namespace Ensi\LaravelInitialEventPropagation;

use Ensi\InitialEventPropagation\InitialEventHolder;
use Illuminate\Support\ServiceProvider;

class LaravelInitialEventPropagationServiceProvider extends ServiceProvider
{
    public const CONFIG_FILE_NAME = 'initial-event-propagation.php';

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/' . self::CONFIG_FILE_NAME => config_path(self::CONFIG_FILE_NAME),
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/' . self::CONFIG_FILE_NAME,
            'initial-event-propagation'
        );

        $this->app->scoped(InitialEventHolder::class, fn () => new InitialEventHolder());
    }
}

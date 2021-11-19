<?php

namespace Ensi\LaravelInitialEventPropagation;

use Ensi\InitialEventPropagation\InitialEventDTO;
use Ensi\InitialEventPropagation\InitialEventHolder;
use Illuminate\Container\Container;

class SetInitialEventArtisanMiddleware
{
    public function handle(): void
    {
        Container::getInstance()->make(InitialEventHolder::class)->setInitialEvent(InitialEventDTO::fromScratch(
            app: config('initial-event-propagation.app_code'),
            entrypoint: $this->extractEntrypointFromInput(),
        ));
    }

    protected function extractEntrypointFromInput(): string
    {
        $argv = $_SERVER['argv'] ?? [];
        $argvWithoutOptions = array_filter($argv, fn ($arg) => !str_starts_with($arg, '-'));

        return implode(" ", $argvWithoutOptions);
    }
}

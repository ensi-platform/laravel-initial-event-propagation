<?php

namespace Ensi\LaravelInitiatorPropagation;


use Ensi\InitiatorPropagation\InitiatorDTO;
use Ensi\InitiatorPropagation\InitiatorHolder;
use Illuminate\Container\Container;

class SetInitiatorArtisanMiddleware
{
    public function handle(): void
    {
        Container::getInstance()->make(InitiatorHolder::class)->setInitiator(InitiatorDTO::fromScratch(
            app: config('initiator-propagation.app_code'),
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

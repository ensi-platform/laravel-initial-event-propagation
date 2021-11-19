<?php

namespace Ensi\LaravelInitialEventPropagation;

use Ensi\InitialEventPropagation\InitialEventDTO;
use Ensi\InitialEventPropagation\InitialEventHolder;
use Illuminate\Container\Container;

class Job
{
    public ?InitialEventDTO $initialEvent = null;

    public function __construct()
    {
        $this->initialEvent = Container::getInstance()->make(InitialEventHolder::class)->getInitialEvent();
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new ParseInitialEventJobMiddleware()];
    }
}

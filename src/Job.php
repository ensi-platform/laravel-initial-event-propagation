<?php

namespace Ensi\LaravelInitiatorPropagation;

use Ensi\InitiatorPropagation\InitiatorDTO;
use Ensi\InitiatorPropagation\InitiatorHolder;
use Illuminate\Container\Container;

class Job
{
    public ?InitiatorDTO $initiator = null;

    public function __construct()
    {
        $this->initiator = Container::getInstance()->make(InitiatorHolder::class)->getInitiator();
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new ParseInitiatorJobMiddleware()];
    }
}

<?php

namespace Ensi\LaravelInitialEventPropagation;

use Ensi\InitialEventPropagation\InitialEventDTO;
use Ensi\InitialEventPropagation\InitialEventHolder;
use Illuminate\Container\Container;
use Spatie\QueueableAction\ActionJob as SpatieActionJob;

class ActionJob extends SpatieActionJob
{
    public ?InitialEventDTO $initialEvent = null;

    public function __construct($action, array $parameters = [])
    {
        $this->initialEvent = Container::getInstance()->make(InitialEventHolder::class)->getInitialEvent();
        parent::__construct($action, $parameters);
    }

    public function middleware(): array
    {
        return array_merge($this->middleware, [new ParseInitialEventJobMiddleware()]);
    }
}

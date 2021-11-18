<?php

namespace Ensi\LaravelInitiatorPropagation;

use Ensi\InitiatorPropagation\InitiatorDTO;
use Ensi\InitiatorPropagation\InitiatorHolder;
use Illuminate\Container\Container;
use Spatie\QueueableAction\ActionJob as SpatieActionJob;

class ActionJob extends SpatieActionJob
{
    public ?InitiatorDTO $initiator = null;

    public function __construct($action, array $parameters = [])
    {
        $this->initiator = Container::getInstance()->make(InitiatorHolder::class)->getInitiator();
        parent::__construct($action, $parameters);
    }

    public function middleware(): array
    {
        return array_merge($this->middleware, [new ParseInitiatorJobMiddleware()]);
    }
}

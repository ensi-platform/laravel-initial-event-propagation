<?php

namespace Ensi\LaravelInitialEventPropagation;

use Ensi\InitialEventPropagation\InitialEventHolder;
use Illuminate\Support\Facades\Facade;

class InitialEventHolderFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return InitialEventHolder::class;
    }
}

<?php

namespace Ensi\LaravelInitialEventPropagation;

use Ensi\InitialEventPropagation\InitialEventHolder;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Ensi\InitialEventPropagation\InitialEventHolder getInstance()
 * @method static void resetInstances()
 * @method static \Ensi\InitialEventPropagation\InitialEventHolder setInitialEvent(\Ensi\InitialEventPropagation\InitialEventDTO|null $initialEvent)
 * @method static \Ensi\InitialEventPropagation\InitialEventDTO getInitialEvent()
 *
 * @see \Ensi\InitialEventPropagation\InitialEventHolder
 */
class InitialEventHolderFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return InitialEventHolder::class;
    }
}

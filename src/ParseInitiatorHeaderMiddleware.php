<?php

namespace Ensi\LaravelInitialEventPropagation;

use Closure;
use Ensi\InitialEventPropagation\Config;
use Ensi\InitialEventPropagation\InitialEventDTO;
use Ensi\InitialEventPropagation\InitialEventHolder;
use Illuminate\Http\Request;

class ParseInitialEventHeaderMiddleware
{
    public function __construct(protected InitialEventHolder $initialEventHolder)
    {
    }

    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->hasHeader(Config::REQUEST_HEADER)) {
            $initialEvent = InitialEventDTO::fromSerializedString($request->header(Config::REQUEST_HEADER));
            $this->initialEventHolder->setInitialEvent($initialEvent);
        }

        return $next($request);
    }
}

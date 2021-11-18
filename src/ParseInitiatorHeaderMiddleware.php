<?php

namespace Ensi\LaravelInitiatorPropagation;

use Closure;
use Ensi\InitiatorPropagation\Config;
use Ensi\InitiatorPropagation\InitiatorDTO;
use Ensi\InitiatorPropagation\InitiatorHolder;
use Illuminate\Http\Request;

class ParseInitiatorHeaderMiddleware
{
    public function __construct(protected InitiatorHolder $initiatorHolder)
    {
    }

    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->hasHeader(Config::REQUEST_HEADER)) {
            $initiator = InitiatorDTO::fromSerializedString($request->header(Config::REQUEST_HEADER));
            $this->initiatorHolder->setInitiator($initiator);
        }

        return $next($request);
    }
}

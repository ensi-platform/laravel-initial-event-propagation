<?php

namespace Ensi\LaravelInitiatorPropagation;

use Closure;
use Ensi\InitiatorPropagation\InitiatorDTO;
use Ensi\InitiatorPropagation\InitiatorHolder;
use Illuminate\Http\Request;

class SetRequestInititatorMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = $request->user();
        $initiator =  InitiatorDTO::fromScratch(
            userId : $user ? $user->getId() : "",
            userType: $user ? config('initiator-propagation.default_user_type', '') : "",
            app: config('initiator-propagation.app_code', ''),
            entrypoint: $this->extractEntrypoint($request)
        );

        InitiatorHolder::getInstance()->setInitiator($initiator);

        return $next($request);
    }

    protected function extractEntrypoint(Request $request): string
    {
        if ($request->route()?->uri) {
            return "/" . ltrim($request->route()?->uri, "/");
        }

        return $request->getRequestUri();
    }
}

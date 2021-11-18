<?php

namespace Ensi\LaravelInitiatorPropagation;

use Closure;
use Ensi\InitiatorPropagation\InitiatorDTO;
use Ensi\InitiatorPropagation\InitiatorHolder;
use Illuminate\Container\Container;
use Illuminate\Http\Request;

class SetInitiatorHttpMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = $request->user();
        $config = config('initiator-propagation', []);
        $mc = config('initiator-propagation.set_initiator_http_middleware', []);

        $initiator = InitiatorDTO::fromScratch(
            userId: $user ? $user->getId() : "",
            userType: $user ? config('initiator-propagation.set_initiator_http_middleware.default_user_type', '') : "",
            app: !empty($mc['app_code_header']) ? $request->header($mc['app_code_header']) : ($config['app_code'] ?? ''),
            entrypoint: $this->extractEntrypoint($request),
            correlationId: !empty($mc['correlation_id_header']) ? $request->header($mc['correlation_id_header']) : '',
            startedAt: !empty($mc['started_at_header']) ? $request->header($mc['started_at_header']) : ''
        );

        Container::getInstance()->make(InitiatorHolder::class)->setInitiator($initiator);

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

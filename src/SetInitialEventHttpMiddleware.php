<?php

namespace Ensi\LaravelInitialEventPropagation;

use Closure;
use Ensi\InitialEventPropagation\InitialEventDTO;
use Ensi\InitialEventPropagation\InitialEventHolder;
use Illuminate\Container\Container;
use Illuminate\Http\Request;

class SetInitialEventHttpMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = $request->user();
        $config = config('initial-event-propagation', []);
        $mc = config('initial-event-propagation.set_initial_event_http_middleware', []);

        $initialEvent = InitialEventDTO::fromScratch(
            userId: $user ? $user->getId() : "",
            userType: $user ? $mc['default_user_type'] : "",
            app: !empty($mc['app_code_header']) ? $request->header($mc['app_code_header']) : ($config['app_code'] ?? ''),
            entrypoint: $this->extractEntrypoint($request),
            correlationId: !empty($mc['correlation_id_header']) ? $request->header($mc['correlation_id_header']) : '',
            timestamp: !empty($mc['timestamp_header']) ? $request->header($mc['timestamp_header']) : ''
        );

        Container::getInstance()->make(InitialEventHolder::class)->setInitialEvent($initialEvent);

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

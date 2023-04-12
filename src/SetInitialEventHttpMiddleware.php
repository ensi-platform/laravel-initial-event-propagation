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
        $config = config('initial-event-propagation', []);
        $mc = config('initial-event-propagation.set_initial_event_http_middleware', []);
        $holder =  Container::getInstance()->make(InitialEventHolder::class);
        $existingEvent = $holder->getInitialEvent();

        if (!$existingEvent || empty($mc['preserve_existing_event'])) {

            $user = null;
            if ($mc['try_auth'] ?? true) {
                $user = $request->user();
            }

            $initialEvent = InitialEventDTO::fromScratch(
                app: !empty($mc['app_code_header']) ? $request->header($mc['app_code_header'], '') : ($config['app_code'] ?? ''),
                entrypoint: $this->extractEntrypoint($request),
                userId: $user ? $user->getAuthIdentifier() : "",
                userType: $user ? $mc['default_user_type'] : "",
                correlationId: !empty($mc['correlation_id_header']) ? $request->header($mc['correlation_id_header'], '') : '',
                timestamp: !empty($mc['timestamp_header']) ? $request->header($mc['timestamp_header'], '') : ''
            );

            $holder->setInitialEvent($initialEvent);
        }

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

<?php

namespace Ensi\LaravelInitialEventPropagation;

use Ensi\InitialEventPropagation\InitialEventHolder;
use Illuminate\Container\Container;

class ParseInitialEventJobMiddleware
{
    /**
     * Process the queued job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next): mixed
    {
        if ($job?->initialEvent) {
            Container::getInstance()->make(InitialEventHolder::class)->setInitialEvent($job->initialEvent);
        }

        return $next($job);
    }
}

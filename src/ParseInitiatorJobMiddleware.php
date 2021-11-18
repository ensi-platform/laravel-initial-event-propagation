<?php

namespace Ensi\LaravelInitiatorPropagation;

use Ensi\InitiatorPropagation\InitiatorHolder;
use Illuminate\Container\Container;

class ParseInitiatorJobMiddleware
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
        if ($job?->initiator) {
            Container::getInstance()->make(InitiatorHolder::class)->setInitiator($job->initiator);
        }

        return $next($job);
    }
}

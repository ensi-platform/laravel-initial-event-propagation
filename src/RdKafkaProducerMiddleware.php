<?php

namespace Ensi\LaravelInitialEventPropagation;

use Closure;
use Ensi\InitialEventPropagation\Config;
use Ensi\InitialEventPropagation\InitialEventHolder;
use Ensi\LaravelPhpRdKafkaProducer\ProducerMessage;

class RdKafkaProducerMiddleware
{
    public function __construct(private InitialEventHolder $initialEventHolder)
    {
    }

    public function handle(ProducerMessage $message, Closure $next): mixed
    {
        $initialEvent = $this->initialEventHolder->getInitialEvent();
        if ($initialEvent) {
            $message->headers = $message->headers ?: [];
            $message->headers[Config::REQUEST_HEADER] = $initialEvent->serialize();
        }

        return $next($message);
    }
}

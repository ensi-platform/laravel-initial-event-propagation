<?php

namespace Ensi\LaravelInitialEventPropagation;

use Closure;
use Ensi\InitialEventPropagation\Config;
use Ensi\InitialEventPropagation\InitialEventDTO;
use Ensi\InitialEventPropagation\InitialEventHolder;
use RdKafka\Message;

class RdKafkaConsumerMiddleware
{
    public function __construct(private InitialEventHolder $initialEventHolder)
    {
    }

    public function handle(Message $message, Closure $next): mixed
    {
        $initialEvent = $this->extractInitialEventFromHeaders($message->headers);
        if ($initialEvent) {
            $this->initialEventHolder->setInitialEvent($initialEvent);
        }

        return $next($message);
    }

    protected function extractInitialEventFromHeaders(?array $headers): ?InitialEventDTO
    {
        $header = $headers[Config::REQUEST_HEADER] ?? '';

        return $header ? InitialEventDTO::fromSerializedString($header) : null;
    }
}

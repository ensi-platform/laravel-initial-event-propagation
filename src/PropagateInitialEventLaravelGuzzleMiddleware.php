<?php

namespace Ensi\LaravelInitialEventPropagation;

use Ensi\InitialEventPropagation\Config;
use Ensi\InitialEventPropagation\InitialEventHolder;
use Illuminate\Container\Container;
use Psr\Http\Message\RequestInterface;

class PropagateInitialEventLaravelGuzzleMiddleware
{
    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, $options) use ($handler) {
            $initialEvent = Container::getInstance()->make(InitialEventHolder::class)->getInitialEvent();

            return $handler(
                $initialEvent ? $request->withHeader(Config::REQUEST_HEADER, $initialEvent->serialize()) : $request,
                $options
            );
        };
    }
}

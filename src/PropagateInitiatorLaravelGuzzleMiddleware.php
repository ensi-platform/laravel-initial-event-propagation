<?php

namespace Ensi\LaravelInitiatorPropagation;

use Ensi\InitiatorPropagation\Config;
use Ensi\InitiatorPropagation\InitiatorHolder;
use Illuminate\Container\Container;
use Psr\Http\Message\RequestInterface;

class PropagateInitiatorLaravelGuzzleMiddleware
{
    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, $options) use ($handler) {
            $inititiator = Container::getInstance()->make(InitiatorHolder::class)->getInitiator();

            return $handler(
                $inititiator ? $request->withHeader(Config::REQUEST_HEADER, $inititiator->serialize()) : $request,
                $options
            );
        };
    }
}

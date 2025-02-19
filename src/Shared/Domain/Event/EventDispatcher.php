<?php

declare(strict_types=1);

namespace Finizens\Shared\Domain\Event;

interface EventDispatcher
{
    public function addListener(string $domainEventClass, EventListener $listener): void;
    public function dispatch(DomainEvent $event): void;
}

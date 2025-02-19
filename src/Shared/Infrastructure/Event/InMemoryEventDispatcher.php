<?php

declare(strict_types=1);

namespace Finizens\Shared\Infrastructure\Event;

use Finizens\Shared\Domain\Event\DomainEvent;
use Finizens\Shared\Domain\Event\EventDispatcher;
use Finizens\Shared\Domain\Event\EventListener;

class InMemoryEventDispatcher implements EventDispatcher
{
    /** @var array<string,EventListener[]> */
    private array $listeners;

    public function __construct()
    {
        $this->listeners = [];
    }

    public function addListener(string $domainEventClass, EventListener $listener): void
    {
        $this->listeners[$domainEventClass][] = $listener;
    }

    public function dispatch(DomainEvent $event): void
    {
        if (isset($this->listeners[get_class($event)])) {
            foreach ($this->listeners[get_class($event)] as $listener) {
                $listener->handle($event);
            }
        }
    }
}

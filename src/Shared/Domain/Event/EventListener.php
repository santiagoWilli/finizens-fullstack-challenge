<?php

declare(strict_types=1);

namespace Finizens\Shared\Domain\Event;

interface EventListener
{
    public function handle(DomainEvent $event): void;
}

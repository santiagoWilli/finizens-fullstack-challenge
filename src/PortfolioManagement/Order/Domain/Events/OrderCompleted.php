<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Domain\Events;

use Finizens\PortfolioManagement\Order\Domain\Order;
use Finizens\PortfolioManagement\Order\Domain\OrderType;
use Finizens\Shared\Domain\Event\DomainEvent;

class OrderCompleted implements DomainEvent
{
    public function __construct(
        private readonly Order $order
    ) {}

    public function getPortfolioId(): int
    {
        return $this->order->getPortfolioId();
    }

    public function getAllocationId(): int
    {
        return $this->order->getAllocationId();
    }

    public function getShares(): int
    {
        return $this->order->getShares();
    }

    public function isBuy(): bool
    {
        return $this->order->getType() === OrderType::Buy;
    }
}

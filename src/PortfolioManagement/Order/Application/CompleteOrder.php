<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Application;

use Finizens\PortfolioManagement\Order\Domain\Events\OrderCompleted;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\OrderNotFound;
use Finizens\PortfolioManagement\Order\Domain\AllocationRepository;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\AllocationSharesWouldBeNegative;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\OrderCannotBeCompleted;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;
use Finizens\PortfolioManagement\Order\Domain\OrderType;
use Finizens\Shared\Domain\Event\EventDispatcher;

class CompleteOrder
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly AllocationRepository $allocationRepository,
        private readonly EventDispatcher $eventDispatcher
    ) {}

    /** @throws OrderNotFound */
    public function __invoke(int $id): void
    {
        $order = $this->orderRepository->find($id);
        try {
            if ($order->getType() === OrderType::Sell) {
                $allocation = $this->allocationRepository->find($order->getAllocationId());
                $allocation->substractShares($order->getShares());
            }
        } catch (AllocationSharesWouldBeNegative) {
            throw new OrderCannotBeCompleted();
        }
        $order->complete();
        $this->orderRepository->save($order);

        $this->eventDispatcher->dispatch(new OrderCompleted($order));
    }
}

<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Application;

use Finizens\PortfolioManagement\Order\Domain\AllocationRepository;
use Finizens\PortfolioManagement\Order\Domain\Order;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;
use Finizens\PortfolioManagement\Order\Domain\OrderType;

class CreateOrder
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly AllocationRepository $allocationRepository
    ) {}

    public function __invoke(int $id, int $portfolioId, int $allocationId, string $type, int $shares): void
    {
        $order = Order::create($id, $portfolioId, $allocationId, $type, $shares);
        if ($order->getType() === OrderType::Sell) {
            $allocation = $this->allocationRepository->find($allocationId);
            $allocation->substractShares($shares);
        }
        $this->orderRepository->save($order);
    }
}

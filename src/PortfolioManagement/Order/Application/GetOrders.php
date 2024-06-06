<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Application;

use Finizens\PortfolioManagement\Order\Domain\Order;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;

class GetOrders
{
    public function __construct(
        private readonly OrderRepository $orderRepository
    ) {}

    /** @return Order[] */
    public function __invoke(int $portfolioId): array
    {
        // this will be the only logic here for the sake of this technical test simplicity
        return $this->orderRepository->findPortfolioPendingOrders($portfolioId);
    }
}

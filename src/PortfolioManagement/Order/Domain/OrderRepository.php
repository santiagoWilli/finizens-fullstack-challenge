<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Domain;

use Finizens\PortfolioManagement\Order\Domain\Exceptions\OrderNotFound;

interface OrderRepository
{
    /** @throws OrderNotFound */
    public function find(int $id): Order;
    /** @return Order[] */
    public function findPortfolioPendingOrders(int $portfolioId): array;
    public function save(Order $order): void;
}

<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Application;

use Finizens\PortfolioManagement\Order\Domain\Order;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;

class CreateOrder
{
    public function __construct(private readonly OrderRepository $repository) {}

    public function __invoke(int $id, int $portfolioId, int $allocationId, string $type, int $shares): void
    {
        $order = Order::create($id, $portfolioId, $allocationId, $type, $shares);
        $this->repository->save($order);
    }
}

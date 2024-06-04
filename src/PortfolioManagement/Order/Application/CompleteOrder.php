<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Application;

use Finizens\PortfolioManagement\Order\Domain\Exceptions\OrderNotFound;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;

class CompleteOrder
{
    public function __construct(
        private readonly OrderRepository $repository
    ) {}

    /** @throws OrderNotFound */
    public function __invoke(int $id): void
    {
        $order = $this->repository->find($id);
        $order->complete();
        $this->repository->save($order);
    }
}

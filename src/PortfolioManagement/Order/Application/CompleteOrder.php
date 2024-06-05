<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Application;

use Finizens\PortfolioManagement\Order\Domain\Events\OrderCompleted;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\OrderNotFound;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;
use Finizens\Shared\Domain\Event\EventDispatcher;

class CompleteOrder
{
    public function __construct(
        private readonly OrderRepository $repository,
        private readonly EventDispatcher $eventDispatcher
    ) {}

    /** @throws OrderNotFound */
    public function __invoke(int $id): void
    {
        $order = $this->repository->find($id);
        $order->complete();
        $this->repository->save($order);

        $this->eventDispatcher->dispatch(new OrderCompleted($order));
    }
}

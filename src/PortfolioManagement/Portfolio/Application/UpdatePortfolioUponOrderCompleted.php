<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Portfolio\Application;

use Finizens\PortfolioManagement\Order\Domain\Events\OrderCompleted;
use Finizens\PortfolioManagement\Portfolio\Domain\Allocation;
use Finizens\PortfolioManagement\Portfolio\Domain\PortfolioRepository;
use Finizens\Shared\Domain\Event\DomainEvent;
use Finizens\Shared\Domain\Event\EventListener;

class UpdatePortfolioUponOrderCompleted implements EventListener
{
    public function __construct(private readonly PortfolioRepository $repository) {}

    public function handle(DomainEvent $event): void
    {
        if (!$event instanceof OrderCompleted) {
            return;
        }

        $portfolio = $this->repository->find($event->getPortfolioId());

        if ($event->isBuy()) {
            if (
                array_search(
                    $event->getAllocationId(),
                    array_map(fn (Allocation $a) => $a->getId(), $portfolio->getAllocations())
                ) === false
            ) {
                $portfolio->addAllocation(
                    Allocation::create($event->getAllocationId(), $event->getShares())
                );
            } else {
                $portfolio->addSharesToAllocation($event->getAllocationId(), $event->getShares());
            }
        } else {
            $portfolio->removeSharesFromAllocation($event->getAllocationId(), $event->getShares());
        }

        $this->repository->save($portfolio);
    }
}

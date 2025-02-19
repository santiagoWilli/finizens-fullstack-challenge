<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Portfolio\Application;

use Finizens\PortfolioManagement\Portfolio\Domain\Allocation;
use Finizens\PortfolioManagement\Portfolio\Domain\Portfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\PortfolioRepository;

class CreatePortfolio
{
    public function __construct(private readonly PortfolioRepository $repository) {}

    public function __invoke(int $id, Allocation ...$allocations): void
    {
        $portfolio = Portfolio::create($id);
        foreach ($allocations as $allocation) {
            $portfolio->addAllocation($allocation);
        }
        $this->repository->save($portfolio);
    }
}

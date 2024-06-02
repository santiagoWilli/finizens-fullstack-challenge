<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Portfolio\Application;

use Finizens\PortfolioManagement\Portfolio\Domain\Portfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\PortfolioRepository;

class FindPortfolio
{
    public function __construct(private readonly PortfolioRepository $repository) {}

    public function __invoke(int $id): Portfolio
    {
        return $this->repository->search($id);
    }
}

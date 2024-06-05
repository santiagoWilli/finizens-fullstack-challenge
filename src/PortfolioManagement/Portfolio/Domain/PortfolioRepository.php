<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Portfolio\Domain;

use Finizens\PortfolioManagement\Portfolio\Domain\Exceptions\PortfolioNotFound;

interface PortfolioRepository
{
    /** @throws PortfolioNotFound */
    public function find(int $id): Portfolio;
    public function save(Portfolio $portfolio): void;
}

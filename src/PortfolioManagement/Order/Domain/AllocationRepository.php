<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Domain;

use Finizens\PortfolioManagement\Order\Domain\Exceptions\AllocationNotFound;

interface AllocationRepository
{
    /** @throws AllocationNotFound */
    public function find(int $id): Allocation;
}

<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Domain;

interface AllocationRepository
{
    public function exists(int $id): bool;
}

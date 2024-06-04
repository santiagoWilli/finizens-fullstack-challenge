<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Domain;

use Finizens\PortfolioManagement\Order\Domain\Exceptions\AllocationSharesWouldBeNegative;

final class AllocationShares
{
    public function __construct(private readonly int $value)
    {
        if ($value < 0) {
            throw new AllocationSharesWouldBeNegative();
        }
    }

    public function substract(int $amount): AllocationShares
    {
        return new AllocationShares($this->value - $amount);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}

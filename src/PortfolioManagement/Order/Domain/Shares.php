<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Domain;

use Finizens\PortfolioManagement\Order\Domain\Exceptions\SharesAreNotPositive;
use Finizens\Shared\Domain\SharesAreNegative;

final class Shares
{
    public function __construct(private readonly int $value)
    {
        if ($value <= 0) {
            throw new SharesAreNotPositive();
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }
}

<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Portfolio\Domain;

use Finizens\PortfolioManagement\Portfolio\Domain\Exceptions\SharesAreNegative;

final class Shares
{
    public function __construct(private readonly int $value)
    {
        if ($value < 0) {
            throw new SharesAreNegative();
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }
}

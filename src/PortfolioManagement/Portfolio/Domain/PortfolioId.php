<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Portfolio\Domain;

final class PortfolioId
{
    public function __construct(private readonly int $value) {}

    public function getValue(): int
    {
        return $this->value;
    }
}

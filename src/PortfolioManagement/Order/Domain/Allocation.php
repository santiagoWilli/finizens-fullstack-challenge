<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Domain;

use Finizens\PortfolioManagement\Order\Domain\Exceptions\AllocationSharesWouldBeNegative;
use Finizens\Shared\Domain\AllocationId;

final class Allocation
{
    private function __construct(
        private readonly AllocationId $id,
        private readonly AllocationShares $shares
    ) {}

    /**
     * @throws AllocationSharesWouldBeNegative
     */
    public static function create(int $id, int $shares): self
    {
        return new self(
            new AllocationId($id),
            new AllocationShares($shares),
        );
    }

    public function substractShares(int $amount): void
    {
        $this->shares->substract($amount);
    }

    public function getId(): int
    {
        return $this->id->getValue();
    }

    public function getShares(): int
    {
        return $this->shares->getValue();
    }
}

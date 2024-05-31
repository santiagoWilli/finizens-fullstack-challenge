<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Portfolio\Domain;

final class Allocation
{
    private function __construct(
        private readonly AllocationId $id,
        private readonly Shares $shares
    ) {}

    public static function create(int $id, int $shares): self
    {
        return new self(new AllocationId($id), new Shares($shares));
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
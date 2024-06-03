<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Portfolio\Domain;

use Finizens\PortfolioManagement\Portfolio\Domain\Exceptions\SharesAreNegative;
use Finizens\Shared\Domain\PortfolioId;

final class Portfolio
{
    /** @var Allocation[] $allocations */
    private array $allocations;

    private function __construct(private readonly PortfolioId $id)
    {
        $this->allocations = [];
    }

    /**
     * @throws SharesAreNegative
     */
    public static function create(int $id): self
    {
        return new self(new PortfolioId($id));
    }

    public function addAllocation(Allocation $allocation): void
    {
        $this->allocations[] = $allocation;
    }

    public function getId(): int
    {
        return $this->id->getValue();
    }

    /** @return Allocation[] */
    public function getAllocations(): array
    {
        return $this->allocations;
    }
}

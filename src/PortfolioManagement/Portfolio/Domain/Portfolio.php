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
        $this->allocations[$allocation->getId()] = $allocation;
    }

    public function addSharesToAllocation(int $allocationId, int $shares): void
    {
        $existingAllocation = $this->allocations[$allocationId];
        $this->allocations[$allocationId] = Allocation::create(
            $existingAllocation->getId(),
            $existingAllocation->getShares() + $shares
        );
    }

    public function removeSharesFromAllocation(int $allocationId, int $shares): void
    {
        $existingAllocation = $this->allocations[$allocationId];
        $updatedAllocation = Allocation::create(
            $existingAllocation->getId(),
            $existingAllocation->getShares() - $shares
        );
        if ($updatedAllocation->getShares() > 0) {
            $this->allocations[$allocationId] = $updatedAllocation;
        } else {
            unset($this->allocations[$allocationId]);
        }
    }

    public function getId(): int
    {
        return $this->id->getValue();
    }

    /** @return Allocation[] */
    public function getAllocations(): array
    {
        return array_values($this->allocations);
    }
}

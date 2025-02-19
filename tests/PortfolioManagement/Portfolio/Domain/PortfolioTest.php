<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Finizens\PortfolioManagement\Portfolio\Domain\Portfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\Allocation;
use Tests\PortfolioManagement\Portfolio\MockPortfolio;

final class PortfolioTest extends TestCase
{
    public function testPortfolioCanBeCreatedOK(): void
    {
        $id = 1;
        $portfolio = Portfolio::create($id);

        $this->assertSame($id, $portfolio->getId());
        $this->assertEmpty($portfolio->getAllocations());
    }

    public function testOneAllocationCanBeAdded(): void
    {
        $portfolioId = 1;
        $portfolio = Portfolio::create($portfolioId);

        $allocation = Allocation::create(id: 3, shares: 5);
        
        $portfolio->addAllocation($allocation);
        
        $this->assertCount(1, $portfolio->getAllocations());
        $this->assertSame($allocation, $portfolio->getAllocations()[0]);
    }

    public function testMultipleAllocationsCanBeAdded(): void
    {
        $portfolioId = 1;
        $portfolio = Portfolio::create($portfolioId);
        
        $allocations = [];
        for ($i = 1; $i <= 3; $i++) {
            $allocation = Allocation::create(id: $i, shares: 5 * $i);
            $allocations[] = $allocation;
            $portfolio->addAllocation($allocation);
        }
        
        $this->assertCount(count($allocations), $portfolio->getAllocations());
        $this->assertSame($allocations, $portfolio->getAllocations());
    }

    public function testSharesCanBeAddedToAnAllocationAndOnlyToThatAllocation(): void
    {
        $portfolioId = 1;
        $allocations = [];
        for ($i = 1; $i <= 3; $i++) {
            $allocations[] = Allocation::create($i, $i * 10);
        }
        $portfolio = MockPortfolio::with($portfolioId, $allocations);

        $allocationToUpdate = $allocations[1];
        $sharesToAdd = 50;
        $portfolio->addSharesToAllocation($allocationToUpdate->getId(), $sharesToAdd);

        foreach ($portfolio->getAllocations() as $key => $allocation) {
            if ($allocation->getId() === $allocationToUpdate->getId()) {
                $this->assertSame(
                    $allocationToUpdate->getShares() + $sharesToAdd,
                    $allocation->getShares()
                );
            } else {
                $this->assertSame(
                    $allocations[$key]->getShares(),
                    $allocation->getShares()
                );
            }
        }
    }
}

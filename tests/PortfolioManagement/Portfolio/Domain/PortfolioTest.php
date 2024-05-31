<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Finizens\PortfolioManagement\Portfolio\Domain\Portfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\Allocation;

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
}

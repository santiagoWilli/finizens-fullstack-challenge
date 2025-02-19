<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Finizens\PortfolioManagement\Portfolio\Domain\Allocation;
use Finizens\PortfolioManagement\Portfolio\Domain\Exceptions\SharesAreNegative;

final class AllocationTest extends TestCase
{
    public function testAllocationCanBeCreatedWithValidValues(): void
    {
        $id = 1;
        $shares = 10;
        
        $allocation = Allocation::create($id, $shares);

        $this->assertSame($id, $allocation->getId());
        $this->assertSame($shares, $allocation->getShares());
    }

    public function testAllocationCannotBeCreatedWithNegativeShares(): void
    {
        $id = 1;
        $shares = -1;

        $this->expectException(SharesAreNegative::class);
        $allocation = Allocation::create($id, $shares);
    }
}

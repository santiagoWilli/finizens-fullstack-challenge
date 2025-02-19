<?php

declare(strict_types=1);

namespace Tests\Finizens\PortfolioManagement\Order\Domain;

use Finizens\PortfolioManagement\Order\Domain\Allocation;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\AllocationSharesWouldBeNegative;
use PHPUnit\Framework\TestCase;

final class AllocationTest extends TestCase
{
    public function testCreateWithValidData(): void
    {
        $allocation = Allocation::create(1, 10);

        $this->assertEquals(1, $allocation->getId());
        $this->assertEquals(10, $allocation->getShares());
    }

    public function testCreateWithNegativeShares(): void
    {
        $this->expectException(AllocationSharesWouldBeNegative::class);
        Allocation::create(1, -1);
    }

    public function testSubstractShares(): void
    {
        $allocation = Allocation::create(1, 10);
        $allocation->substractShares(3);

        $this->expectNotToPerformAssertions();
    }

    public function testSubstractMoreSharesThanAvailableThrowsAnException(): void
    {
        $allocation = Allocation::create(1, 5);

        $this->expectException(AllocationSharesWouldBeNegative::class);
        $allocation->substractShares(10);
    }
}

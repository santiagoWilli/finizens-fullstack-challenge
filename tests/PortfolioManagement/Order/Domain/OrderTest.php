<?php

declare(strict_types=1);

use Finizens\PortfolioManagement\Order\Domain\Exceptions\SharesAreNotPositive;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\UnknownOrderType;
use Finizens\PortfolioManagement\Order\Domain\Order;
use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
{
    public function testOrderCanBeCreatedWithValidDataAndIsNotCompletedUponCreation(): void
    {
        $id = 1;
        $portfolioId = 1;
        $allocationId = 2;
        $type = 'buy';
        $shares = 10;

        $order = Order::create($id, $portfolioId, $allocationId, $type, $shares);

        $this->assertEquals($id, $order->getId());
        $this->assertEquals($portfolioId, $order->getPortfolioId());
        $this->assertEquals($allocationId, $order->getAllocationId());
        $this->assertEquals($type, $order->getType()->value);
        $this->assertEquals($shares, $order->getShares());
        $this->assertEquals(false, $order->isCompleted());
    }

    public function testOrderCannotBeCreatedWithNonGreaterThanZeroShares(): void
    {
        $this->expectException(SharesAreNotPositive::class);
        Order::create(1, 1, 2, 'buy', 0);
    }

    public function testOrderCannotBeCreatedWithInvalidOrderType(): void
    {
        $this->expectException(UnknownOrderType::class);
        Order::create(1, 1, 2, 'invalid', 1);
    }

    public function testOrderCompleteMarksTheOrderAsCompleted(): void
    {
        $order = Order::create(1, 2, 3, 'buy', 5);
        $this->assertFalse($order->isCompleted());
        $order->complete();
        $this->assertTrue($order->isCompleted());
    }
}

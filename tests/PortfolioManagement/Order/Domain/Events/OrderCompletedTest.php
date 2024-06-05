<?php

declare(strict_types=1);

use Finizens\PortfolioManagement\Order\Domain\Events\OrderCompleted;
use Finizens\PortfolioManagement\Order\Domain\Order;
use PHPUnit\Framework\TestCase;

final class OrderCompletedTest extends TestCase
{
    public function testOrderCompleted(): void
    {
        $orderId = 1;
        $portfolioId = 1;
        $allocationId = 2;
        $shares = 10;
        $buyType = 'buy';
        $sellType = 'sell';

        $event = new OrderCompleted(
            Order::create($orderId, $portfolioId, $allocationId, $buyType, $shares)
        );

        $this->assertEquals($orderId, $event->getOrderId());
        $this->assertEquals($portfolioId, $event->getPortfolioId());
        $this->assertEquals($allocationId, $event->getAllocationId());
        $this->assertEquals($shares, $event->getShares());
        $this->assertTrue($event->isBuy());

        $event = new OrderCompleted(
            Order::create($orderId, $portfolioId, $allocationId, $sellType, $shares)
        );
        $this->assertFalse($event->isBuy());
    }
}

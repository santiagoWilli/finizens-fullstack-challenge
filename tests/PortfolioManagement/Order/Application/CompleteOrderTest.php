<?php

declare(strict_types=1);

use Finizens\PortfolioManagement\Order\Application\CompleteOrder;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\OrderNotFound;
use Finizens\PortfolioManagement\Order\Domain\Order;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class CompleteOrderTest extends TestCase
{
    private CompleteOrder $sut;
    private MockObject|OrderRepository $orderRepository;

    public function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->sut = new CompleteOrder($this->orderRepository);
    }

    public function testCompleteOrderThrowsOrderNotFoundWhenTheOrderDoesNotExist(): void
    {
        $orderId = 1;
        $this->orderRepository->expects($this->once())
            ->method('find')
            ->with($orderId)
            ->willThrowException(new OrderNotFound());

        $this->expectException(OrderNotFound::class);
        $this->sut->__invoke($orderId);
    }

    public function testCompleteOrderSavesTheSameOrderButNowAsCompleted(): void
    {
        $order = Order::create(1, 2, 4, 'buy', 10);
        $this->assertFalse($order->isCompleted());
        $this->orderRepository->expects($this->once())
            ->method('find')
            ->with($order->getId())
            ->willReturn($order);
        $this->orderRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(
                fn (Order $completedOrder) => $completedOrder->isCompleted()
            ));

        $this->sut->__invoke($order->getId());
    }
}

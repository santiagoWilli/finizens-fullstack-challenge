<?php

declare(strict_types=1);

use Finizens\PortfolioManagement\Order\Application\CompleteOrder;
use Finizens\PortfolioManagement\Order\Domain\Events\OrderCompleted;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\OrderNotFound;
use Finizens\PortfolioManagement\Order\Domain\Order;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;
use Finizens\Shared\Domain\Event\EventDispatcher;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class CompleteOrderTest extends TestCase
{
    private CompleteOrder $sut;
    private MockObject|OrderRepository $orderRepository;
    private MockObject|EventDispatcher $eventDispatcher;

    public function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->eventDispatcher = $this->createMock(EventDispatcher::class);
        $this->sut = new CompleteOrder($this->orderRepository, $this->eventDispatcher);
    }

    public function testCompleteOrderThrowsOrderNotFoundWhenTheOrderDoesNotExist(): void
    {
        $orderId = 1;
        $this->orderRepository->expects($this->once())
            ->method('find')
            ->with($orderId)
            ->willThrowException(new OrderNotFound());
        $this->eventDispatcher->expects($this->never())
            ->method($this->anything());

        $this->expectException(OrderNotFound::class);
        $this->sut->__invoke($orderId);
    }

    public function testCompleteOrderSavesTheSameOrderButNowAsCompletedAndThenDispatchesTheDomainEvent(): void
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

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with(new OrderCompleted($order));

        $this->sut->__invoke($order->getId());
    }
}

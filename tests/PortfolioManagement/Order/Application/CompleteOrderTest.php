<?php

declare(strict_types=1);

use Finizens\PortfolioManagement\Order\Application\CompleteOrder;
use Finizens\PortfolioManagement\Order\Domain\Allocation;
use Finizens\PortfolioManagement\Order\Domain\AllocationRepository;
use Finizens\PortfolioManagement\Order\Domain\Events\OrderCompleted;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\OrderCannotBeCompleted;
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
    private MockObject|AllocationRepository $allocationRepository;
    private MockObject|EventDispatcher $eventDispatcher;

    public function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->allocationRepository = $this->createMock(AllocationRepository::class);
        $this->eventDispatcher = $this->createMock(EventDispatcher::class);
        $this->sut = new CompleteOrder($this->orderRepository, $this->allocationRepository, $this->eventDispatcher);
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

    public function testCompleteBuyOrderSavesTheSameOrderButNowAsCompletedAndThenDispatchesTheDomainEvent(): void
    {
        $order = Order::create(1, 2, 4, 'buy', 10);
        $this->setUpCorrectCompleteOrder($order);
        $this->allocationRepository->expects($this->never())
            ->method($this->anything());

        $this->sut->__invoke($order->getId());
    }

    public function testCompleteSellOrderSavesTheSameOrderButNowAsCompletedAndThenDispatchesTheDomainEvent(): void
    {
        $allocationId = 4;
        $shares = 10;
        $order = Order::create(1, 2, $allocationId, 'sell', $shares);
        $this->setUpCorrectCompleteOrder($order);
        $this->allocationRepository->expects($this->once())
            ->method('find')
            ->with($allocationId)
            ->willReturn(Allocation::create($allocationId, $shares));

        $this->sut->__invoke($order->getId());
    }

    public function testCompleteSellOrderThrowsExceptionIfTheCurrentAllocationSharesWouldBeLeftNegative(): void
    {
        $allocationId = 4;
        $orderShares = 10;
        $order = Order::create(1, 2, $allocationId, 'sell', $orderShares);
        $this->assertFalse($order->isCompleted());

        $this->orderRepository->expects($this->once())
            ->method('find')
            ->with($order->getId())
            ->willReturn($order);
        $this->allocationRepository->expects($this->once())
            ->method('find')
            ->with($allocationId)
            ->willReturn(Allocation::create($allocationId, $orderShares - 1));

        $this->orderRepository->expects($this->never())
            ->method('save');
        $this->eventDispatcher->expects($this->never())
            ->method($this->anything());

        $this->expectException(OrderCannotBeCompleted::class);
        $this->sut->__invoke($order->getId());
    }

    private function setUpCorrectCompleteOrder(Order $order): void
    {
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
    }
}

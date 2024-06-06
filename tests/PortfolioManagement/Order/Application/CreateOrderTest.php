<?php

declare(strict_types=1);

use Finizens\PortfolioManagement\Order\Application\CreateOrder;
use Finizens\PortfolioManagement\Order\Domain\Allocation;
use Finizens\PortfolioManagement\Order\Domain\AllocationRepository;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\AllocationNotFound;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\AllocationSharesWouldBeNegative;
use Finizens\PortfolioManagement\Order\Domain\Order;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class CreateOrderTest extends TestCase
{
    private CreateOrder $sut;
    private MockObject|OrderRepository $orderRepository;
    private MockObject|AllocationRepository $allocationRepository;

    public function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->allocationRepository = $this->createMock(AllocationRepository::class);
        $this->sut = new CreateOrder($this->orderRepository, $this->allocationRepository);
    }

    public function testCreateBuyOrder(): void
    {
        $id = 1;
        $portfolioId = 1;
        $allocationId = 2;
        $type = 'buy';
        $shares = 10;

        $this->orderRepository->expects($this->once())
            ->method('save')
            ->with(Order::create($id, $portfolioId, $allocationId, $type, $shares));
        $this->allocationRepository->expects($this->never())
            ->method($this->anything());

        $this->sut->__invoke($id, $portfolioId, $allocationId, $type, $shares);
    }

    public function testCreateSellOrderForExistingAllocationWithMoreThanTheOrderShares(): void
    {
        $id = 1;
        $portfolioId = 1;
        $allocationId = 2;
        $type = 'sell';
        $shares = 10;

        $this->allocationRepository->expects($this->once())
            ->method('find')
            ->with($allocationId)
            ->willReturn(Allocation::create(1, $shares + 5));
        $this->orderRepository->expects($this->once())
            ->method('save')
            ->with(Order::create($id, $portfolioId, $allocationId, $type, $shares));

        $this->sut->__invoke($id, $portfolioId, $allocationId, $type, $shares);
    }


    public function testCreateSellOrderForExistingAllocationWithTheExactOrderShares(): void
    {
        $id = 1;
        $portfolioId = 1;
        $allocationId = 2;
        $type = 'sell';
        $shares = 10;

        $this->allocationRepository->expects($this->once())
            ->method('find')
            ->with($allocationId)
            ->willReturn(Allocation::create(1, $shares));
        $this->orderRepository->expects($this->once())
            ->method('save')
            ->with(Order::create($id, $portfolioId, $allocationId, $type, $shares));

        $this->sut->__invoke($id, $portfolioId, $allocationId, $type, $shares);
    }

    public function testCreateSellOrderForExistingAllocationWithLessThanTheOrderShares(): void
    {
        $id = 1;
        $portfolioId = 1;
        $allocationId = 2;
        $type = 'sell';
        $shares = 10;

        $this->allocationRepository->expects($this->once())
            ->method('find')
            ->with($allocationId)
            ->willReturn(Allocation::create(1, $shares - 1));
        $this->orderRepository->expects($this->never())
            ->method('save');

        $this->expectException(AllocationSharesWouldBeNegative::class);
        $this->sut->__invoke($id, $portfolioId, $allocationId, $type, $shares);
    }

    public function testCreateSellOrderForInexistingAllocation(): void
    {
        $id = 1;
        $portfolioId = 1;
        $allocationId = 2;
        $type = 'sell';
        $shares = 10;

        $this->allocationRepository->expects($this->once())
            ->method('find')
            ->with($allocationId)
            ->willThrowException(new AllocationNotFound());
        $this->orderRepository->expects($this->never())
            ->method($this->anything());

        $this->expectException(AllocationNotFound::class);
        $this->sut->__invoke($id, $portfolioId, $allocationId, $type, $shares);
    }
}

<?php

declare(strict_types=1);

use Finizens\PortfolioManagement\Order\Application\CreateOrder;
use Finizens\PortfolioManagement\Order\Domain\Order;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class CreateOrderTest extends TestCase
{
    private CreateOrder $sut;
    private MockObject|OrderRepository $repository;

    public function setUp(): void
    {
        $this->repository = $this->createMock(OrderRepository::class);
        $this->sut = new CreateOrder($this->repository);
    }

    public function testCreateOrder(): void
    {
        $id = 1;
        $portfolioId = 1;
        $allocationId = 2;
        $type = 'buy';
        $shares = 10;

        $this->repository->expects($this->once())
            ->method('save')
            ->with(Order::create($id, $portfolioId, $allocationId, $type, $shares));

        $this->sut->__invoke($id, $portfolioId, $allocationId, $type, $shares);
    }
}

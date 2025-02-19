<?php

declare(strict_types=1);

use Finizens\PortfolioManagement\Order\Application\GetOrders;
use Finizens\PortfolioManagement\Order\Domain\Order;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class GetOrdersTest extends TestCase
{
    private GetOrders $sut;
    private MockObject|OrderRepository $orderRepository;

    public function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->sut = new GetOrders($this->orderRepository);
    }

    public function testGetPendingOrders(): void
    {
        $portfolioId = 1;
        $orders = [
            Order::create(1, $portfolioId, 1, 'buy', 10),
            Order::create(2, $portfolioId, 2, 'sell', 5)
        ];

        $this->orderRepository->expects($this->once())
            ->method('findPortfolioPendingOrders')
            ->with($portfolioId)
            ->willReturn($orders);

        $this->assertSame($orders, $this->sut->__invoke($portfolioId));
    }
}

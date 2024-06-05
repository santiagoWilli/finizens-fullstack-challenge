<?php

declare(strict_types=1);

use Finizens\PortfolioManagement\Order\Domain\Events\OrderCompleted;
use Finizens\PortfolioManagement\Portfolio\Application\UpdatePortfolioUponOrderCompleted;
use Finizens\PortfolioManagement\Portfolio\Domain\Allocation;
use Finizens\PortfolioManagement\Portfolio\Domain\Portfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\PortfolioRepository;
use Finizens\Shared\Domain\Event\DomainEvent;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\PortfolioManagement\Portfolio\MockPortfolio;

final class UpdatePortfolioUponOrderCompletedTest extends TestCase
{
    private UpdatePortfolioUponOrderCompleted $sut;
    private MockObject|PortfolioRepository $repository;

    public function setUp(): void
    {
        $this->repository = $this->createMock(PortfolioRepository::class);
        $this->sut = new UpdatePortfolioUponOrderCompleted($this->repository);
    }

    public function testNonOrderCompletedEventIsIgnored(): void
    {
        $event = $this->createMock(DomainEvent::class);
        $this->repository->expects($this->never())
            ->method($this->anything());
        $this->sut->handle($event);
    }

    public function testUpdatePortfolioUponABuyOrderWithANewAllocation(): void
    {
        $portfolioId = 1;
        $allocationId = 3;
        $shares = 5;

        $event = $this->mockOrderCompletedEvent($portfolioId, $allocationId, $shares);
        $event->method('isBuy')->willReturn(true);

        $allocations = [];
        for ($i = 1; $i < $allocationId; $i++) {
            $allocations[] = Allocation::create($i, $i * 10);
        }
        $portfolio = MockPortfolio::with($portfolioId, $allocations);

        $this->repository->expects($this->once())
            ->method('find')
            ->willReturn($portfolio);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Portfolio $portfolio) use ($allocations, $allocationId, $shares) {
                $portfolioAllocations = $portfolio->getAllocations();
                return (
                    count($portfolioAllocations) === count($allocations) + 1 &&
                    end($portfolioAllocations)->getId() === $allocationId &&
                    end($portfolioAllocations)->getShares() === $shares
                );
            }));

        $this->sut->handle($event);
    }

    public function testUpdatePortfolioUponABuyOrderWithAnExistingAllocation(): void
    {
        $portfolioId = 1;
        $allocationId = 3;
        $shares = 5;

        $event = $this->mockOrderCompletedEvent($portfolioId, $allocationId, $shares);
        $event->method('isBuy')->willReturn(true);

        $allocations = [];
        for ($i = 1; $i <= $allocationId + 1; $i++) {
            $allocations[] = Allocation::create($i, $i * 10);
        }
        $savedPortfolio = MockPortfolio::with($portfolioId, $allocations);

        $this->repository->expects($this->once())
            ->method('find')
            ->willReturn($savedPortfolio);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Portfolio $updatedPortfolio) use ($allocations, $allocationId, $shares) {
                $updatedAllocations = $updatedPortfolio->getAllocations();
                $updatedAllocationIndex = array_search(
                    $allocationId,
                    array_map(fn ($a) => $a->getId(), $updatedAllocations)
                );
                $updatedAllocation = $updatedAllocations[$updatedAllocationIndex];
                $savedAllocation = $allocations[$updatedAllocationIndex];
                return (
                    count($updatedAllocations) === count($allocations) &&
                    $updatedAllocation->getShares() === ($savedAllocation->getShares() + $shares)
                );
            }));

        $this->sut->handle($event);
    }

    private function mockOrderCompletedEvent(int $portfolioId, int $allocationId, int $shares): OrderCompleted|MockObject
    {
        $event = $this->createMock(OrderCompleted::class);
        $event->method('getPortfolioId')->willReturn($portfolioId);
        $event->method('getAllocationId')->willReturn($allocationId);
        $event->method('getShares')->willReturn($shares);
        return $event;
    }
}

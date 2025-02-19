<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Finizens\PortfolioManagement\Portfolio\Application\CreatePortfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\Allocation;
use Finizens\PortfolioManagement\Portfolio\Domain\PortfolioRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\PortfolioManagement\Portfolio\MockPortfolio;

final class CreatePortfolioTest extends TestCase
{
    private CreatePortfolio $sut;
    private MockObject $repository;

    public function setUp(): void
    {
        /** @var PortfolioRepository */
        $this->repository = $this->createMock(PortfolioRepository::class);
        $this->sut = new CreatePortfolio($this->repository);
    }

    public function testCreatePortfolio(): void
    {
        $id = 1;
        $allocations = [];
        for ($i = 1; $i <= 3; $i++) {
            $allocations[] = Allocation::create($i, $i * 10);
        }

        $this->repository->expects($this->once())
            ->method('save')
            ->with(MockPortfolio::with($id, $allocations));

        $this->sut->__invoke($id, ...$allocations);
    }
}

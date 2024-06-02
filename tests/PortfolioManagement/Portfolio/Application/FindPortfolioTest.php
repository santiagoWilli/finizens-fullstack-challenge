<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Finizens\PortfolioManagement\Portfolio\Application\FindPortfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\Portfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\Allocation;
use Finizens\PortfolioManagement\Portfolio\Domain\PortfolioRepository;
use PHPUnit\Framework\MockObject\MockObject;

final class FindPortfolioTest extends TestCase
{
    private FindPortfolio $sut;
    private MockObject $repository;

    public function setUp(): void
    {
        /** @var PortfolioRepository */
        $this->repository = $this->createMock(PortfolioRepository::class);
        $this->sut = new FindPortfolio($this->repository);
    }

    public function testFindPortfolio(): void
    {
        $id = 1;
        $allocations = [];
        for ($i = 1; $i <= 3; $i++) {
            $allocations[] = Allocation::create($i, $i * 10);
        }
        $expected = self::getInstanceOfPortfolioFrom($id, $allocations);

        $this->repository->expects($this->once())
            ->method('search')
            ->with($id)
            ->willReturn($expected);

        $this->assertEquals($expected, $this->sut->__invoke($id));
    }

    private static function getInstanceOfPortfolioFrom(int $id, array $allocations): Portfolio
    {
        $portfolio = Portfolio::create($id);
        foreach ($allocations as $allocation) {
            $portfolio->addAllocation($allocation);
        }
        return $portfolio;
    }
}

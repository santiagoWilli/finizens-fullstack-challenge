<?php

declare(strict_types=1);

namespace Tests\Finizens\PortfolioManagement\Portfolio\Infrastructure;

use Finizens\PortfolioManagement\Portfolio\Domain\Portfolio;
use Finizens\PortfolioManagement\Portfolio\Infrastructure\MySQLPortfolioRepository;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;

class MySQLPortfolioRepositoryTest extends TestCase
{
    private PDO|\PHPUnit\Framework\MockObject\MockObject $pdo;
    private MySQLPortfolioRepository $sut;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->sut = new MySQLPortfolioRepository($this->pdo);
    }

    public function testSaveRollbacksWhenTheresAnError(): void
    {
        $this->expectException(PDOException::class);

        $portfolio = Portfolio::create(1);

        $this->pdo->expects($this->once())
            ->method('beginTransaction');
        $this->pdo->method('prepare')
            ->willThrowException(new PDOException());
        $this->pdo->expects($this->once())
            ->method('rollBack');

        $this->sut->save($portfolio);
    }
}

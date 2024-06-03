<?php

declare(strict_types=1);

namespace Tests\Finizens\PortfolioManagement\Portfolio\Infrastructure;

use Finizens\PortfolioManagement\Order\Domain\Exceptions\OrderAlreadyExists;
use Finizens\PortfolioManagement\Order\Domain\Order;
use Finizens\PortfolioManagement\Order\Infrastructure\MySQLOrderRepository;
use PDO;
use PDOException;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class MySQLOrderRepositoryTest extends TestCase
{
    private PDO|\PHPUnit\Framework\MockObject\MockObject $pdo;
    private MySQLOrderRepository $sut;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->sut = new MySQLOrderRepository($this->pdo);
    }

    public function testSaveThrowsExceptionWhenTheOrderAlreadyExists(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willThrowException(new PDOException('', 23000));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->anything())
            ->willReturn($stmt);
        $this->expectException(OrderAlreadyExists::class);
        $this->sut->save(Order::create(1, 1, 1, 'buy', 1));
    }
}

<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Portfolio\Infrastructure;

use Finizens\PortfolioManagement\Portfolio\Domain\Allocation;
use Finizens\PortfolioManagement\Portfolio\Domain\Portfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\PortfolioRepository;
use PDO;
use PDOException;

final class MySQLPortfolioRepository implements PortfolioRepository
{
    public function __construct(private readonly PDO $connection) {}

    public function search(int $id): Portfolio
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM portfolios WHERE id = :id;');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $portfolioData = $stmt->fetch(PDO::FETCH_ASSOC);
            $portfolio = Portfolio::create((int) $portfolioData['id']);

            $allocationsStmt = $this->connection->prepare('SELECT * FROM allocations WHERE portfolio_id = :id;');
            $allocationsStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $allocationsStmt->execute();

            while ($allocationData = $allocationsStmt->fetch(PDO::FETCH_ASSOC)) {
                $allocation = Allocation::create(
                    (int) $allocationData['id'],
                    (int) $allocationData['shares']
                );
                $portfolio->addAllocation($allocation);
            }

            return $portfolio;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function save(Portfolio $portfolio): void
    {
        try {
            $this->connection->beginTransaction();

            $stmt = $this->connection->prepare('INSERT INTO portfolios (id) VALUES (:id) ON DUPLICATE KEY UPDATE id = :id;');
            $stmt->bindParam(':id', $portfolio->getId(), PDO::PARAM_INT);
            $stmt->execute();

            $deleteAllocationsStmt = $this->connection->prepare('DELETE FROM allocations WHERE portfolio_id = :portfolio_id;');
            $deleteAllocationsStmt->bindParam(':portfolio_id', $portfolio->getId(), PDO::PARAM_INT);
            $deleteAllocationsStmt->execute();

            $insertAllocationStmt = $this->connection->prepare('INSERT INTO allocations (id, portfolio_id, shares) VALUES (:id, :portfolio_id, :shares);');
            foreach ($portfolio->getAllocations() as $allocation) {
                $insertAllocationStmt->bindParam(':id', $allocation->getId(), PDO::PARAM_INT);
                $insertAllocationStmt->bindParam(':portfolio_id', $portfolio->getId(), PDO::PARAM_INT);
                $insertAllocationStmt->bindParam(':shares', $allocation->getShares(), PDO::PARAM_INT);
                $insertAllocationStmt->execute();
            }

            $this->connection->commit();
        } catch (PDOException $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}

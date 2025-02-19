<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Infrastructure;

use Finizens\PortfolioManagement\Order\Domain\Allocation;
use Finizens\PortfolioManagement\Order\Domain\AllocationRepository;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\AllocationNotFound;
use PDO;

final class MySQLAllocationRepository implements AllocationRepository
{
    public function __construct(private PDO $pdo) {}

    public function find(int $id): Allocation
    {
        $stmt = $this->pdo->prepare('SELECT * FROM allocations WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $allocationData = $stmt->fetch();

        if (!$allocationData) {
            throw new AllocationNotFound();
        }

        return Allocation::create($allocationData['id'], $allocationData['shares']);
    }
}

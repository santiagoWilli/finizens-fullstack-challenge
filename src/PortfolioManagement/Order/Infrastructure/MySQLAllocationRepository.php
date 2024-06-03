<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Infrastructure;

use Finizens\PortfolioManagement\Order\Domain\AllocationRepository;
use PDO;

final class MySQLAllocationRepository implements AllocationRepository
{
    public function __construct(private PDO $pdo) {}

    public function exists(int $id): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM allocations WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
}

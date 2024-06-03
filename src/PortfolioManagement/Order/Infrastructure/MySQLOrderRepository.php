<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Infrastructure;

use Finizens\PortfolioManagement\Order\Domain\Exceptions\OrderAlreadyExists;
use Finizens\PortfolioManagement\Order\Domain\Order;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;
use PDO;
use PDOException;

final class MySQLOrderRepository implements OrderRepository
{
    public function __construct(private PDO $pdo) {}

    public function save(Order $order): void
    {
        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO orders (id, portfolio_id, allocation_id, type, shares)
                VALUES (:id, :portfolio_id, :allocation_id, :type, :shares)
            ');

            $stmt->execute([
                ':id' => $order->getId(),
                ':portfolio_id' => $order->getPortfolioId(),
                ':allocation_id' => $order->getAllocationId(),
                ':type' => $order->getType(),
                ':shares' => $order->getShares(),
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new OrderAlreadyExists();
            }
            throw $e;
        }
    }
}

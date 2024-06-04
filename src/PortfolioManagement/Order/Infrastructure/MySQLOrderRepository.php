<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Infrastructure;

use Finizens\PortfolioManagement\Order\Domain\Exceptions\OrderNotFound;
use Finizens\PortfolioManagement\Order\Domain\Order;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;
use PDO;

final class MySQLOrderRepository implements OrderRepository
{
    public function __construct(private PDO $pdo) {}

    public function find(int $id): Order
    {
        $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $orderData = $stmt->fetch();

        if (!$orderData) {
            throw new OrderNotFound();
        }

        return Order::create($orderData['id'], $orderData['portfolio_id'], $orderData['allocation_id'], $orderData['type'], $orderData['shares']);
    }

    public function save(Order $order): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO orders (id, portfolio_id, allocation_id, type, shares)
            VALUES (:id, :portfolio_id, :allocation_id, :type, :shares)
            ON DUPLICATE KEY UPDATE
                portfolio_id = VALUES(portfolio_id),
                allocation_id = VALUES(allocation_id),
                type = VALUES(type),
                shares = VALUES(shares);
        ');

        $stmt->execute([
            ':id' => $order->getId(),
            ':portfolio_id' => $order->getPortfolioId(),
            ':allocation_id' => $order->getAllocationId(),
            ':type' => $order->getType()->value,
            ':shares' => $order->getShares(),
        ]);
    }
}

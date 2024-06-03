<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Infrastructure;

use Finizens\PortfolioManagement\Order\Domain\Order;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;

final class MySQLOrderRepository implements OrderRepository
{
    public function save(Order $order): void {}
}

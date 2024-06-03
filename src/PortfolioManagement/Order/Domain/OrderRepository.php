<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Domain;

use Finizens\PortfolioManagement\Order\Domain\Exceptions\OrderAlreadyExists;

interface OrderRepository
{
    /** @throws OrderAlreadyExists */
    public function save(Order $order): void;
}

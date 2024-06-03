<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Domain;

interface OrderRepository
{
    public function save(Order $order): void;
}

<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Domain;

enum OrderType: string
{
    case Buy = 'buy';
}

<?php

declare(strict_types=1);

namespace Tests\PortfolioManagement\Portfolio;

use Finizens\PortfolioManagement\Portfolio\Domain\Portfolio;

final class MockPortfolio
{
    public static function with(int $id, array $allocations): Portfolio
    {
        $portfolio = Portfolio::create($id);
        foreach ($allocations as $allocation) {
            $portfolio->addAllocation($allocation);
        }
        return $portfolio;
    }
}

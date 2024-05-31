<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Portfolio\Domain;

interface PortfolioRepository
{
    public function save(Portfolio $portfolio);
}

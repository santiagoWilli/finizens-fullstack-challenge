<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Portfolio\Infrastructure;

use Finizens\PortfolioManagement\Portfolio\Domain\Portfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\PortfolioRepository;

final class DummyPortfolioRepository implements PortfolioRepository
{
    public function save(Portfolio $portfolio): void {}
}

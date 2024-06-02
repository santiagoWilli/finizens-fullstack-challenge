<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Portfolio\Domain;

interface PortfolioRepository
{
    public function search(int $id): Portfolio;
    public function save(Portfolio $portfolio): void;
}
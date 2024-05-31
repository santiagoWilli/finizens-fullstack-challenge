<?php

declare(strict_types=1);

namespace Tests\PortolioManagement\Portfolio\Domain;

use PHPUnit\Framework\TestCase;
use Finizens\PortfolioManagement\Portfolio\Domain\Shares;
use Finizens\PortfolioManagement\Portfolio\Domain\Exceptions\SharesAreNegative;

final class SharesTest extends TestCase
{
    public function testSharesCanBeCreatedWithPositiveValue(): void
    {
        $shares = new Shares(10);
        $this->assertSame(10, $shares->getValue());
    }

    public function testSharesCanBeCreatedWithZeroValue(): void
    {
        $shares = new Shares(0);
        $this->assertSame(0, $shares->getValue());
    }

    public function testSharesCannotBeCreatedWithNegativeValue(): void
    {
        $this->expectException(SharesAreNegative::class);
        new Shares(-1);
    }
}
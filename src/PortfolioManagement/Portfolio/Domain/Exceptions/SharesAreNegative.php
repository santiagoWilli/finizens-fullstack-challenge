<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Portfolio\Domain\Exceptions;

use Exception;

final class SharesAreNegative extends Exception
{
    public function __construct() {}
}

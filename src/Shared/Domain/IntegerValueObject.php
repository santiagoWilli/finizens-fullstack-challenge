<?php

declare(strict_types=1);

namespace Finizens\Shared\Domain;

abstract class IntegerValueObject
{
    public function __construct(private readonly int $value) {}

    public function getValue(): int
    {
        return $this->value;
    }
}

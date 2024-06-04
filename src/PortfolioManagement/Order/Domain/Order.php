<?php

declare(strict_types=1);

namespace Finizens\PortfolioManagement\Order\Domain;

use Finizens\PortfolioManagement\Order\Domain\Exceptions\SharesAreNotPositive;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\UnknownOrderType;
use Finizens\Shared\Domain\AllocationId;
use Finizens\Shared\Domain\PortfolioId;
use ValueError;

final class Order
{
    private function __construct(
        private readonly OrderId $id,
        private readonly PortfolioId $portfolioId,
        private readonly AllocationId $allocationId,
        private readonly OrderType $type,
        private readonly OrderShares $shares
    ) {}

    /**
     * @throws UnknownOrderType
     * @throws SharesAreNotPositive
     */
    public static function create(int $id, int $portfolioId, int $allocationId, string $type, int $shares): self
    {
        try {
            $orderType = OrderType::from($type);
        } catch (ValueError) {
            throw new UnknownOrderType();
        }
        return new self(
            new OrderId($id),
            new PortfolioId($portfolioId),
            new AllocationId($allocationId),
            $orderType,
            new OrderShares($shares),
        );
    }

    public function getId(): int
    {
        return $this->id->getValue();
    }

    public function getPortfolioId(): int
    {
        return $this->portfolioId->getValue();
    }

    public function getAllocationId(): int
    {
        return $this->allocationId->getValue();
    }

    public function getType(): OrderType
    {
        return $this->type;
    }

    public function getShares(): int
    {
        return $this->shares->getValue();
    }
}

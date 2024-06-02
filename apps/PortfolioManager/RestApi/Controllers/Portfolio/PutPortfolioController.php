<?php

namespace Finizens\Apps\PortfolioManager\RestAPI\Controllers\Portfolio;

use Finizens\PortfolioManagement\Portfolio\Application\CreatePortfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\Allocation;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class PutPortfolioController
{
    public function __construct(private readonly CreatePortfolio $createPortfolio) {}

    public function __invoke(Request $request, Response $response, $id): Response
    {
        $body = $request->getParsedBody();

        $allocations = [];
        $rawAllocations = $body['allocations'];
        foreach ($rawAllocations as $rawAllocation) {
            if (!isset($rawAllocation['id']) || !isset($rawAllocation['shares'])) {
                return $response
                    ->withStatus(400)
                    ->withHeader('Content-Type', 'application/json');
            }
            $allocations[] = Allocation::create(
                $rawAllocation['id'],
                $rawAllocation['shares']
            );
        }

        ($this->createPortfolio)((int) $id, ...$allocations);

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        }
}

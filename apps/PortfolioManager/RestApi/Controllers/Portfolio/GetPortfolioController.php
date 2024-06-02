<?php

namespace Finizens\Apps\PortfolioManager\RestAPI\Controllers\Portfolio;

use Finizens\PortfolioManagement\Portfolio\Application\FindPortfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\Allocation;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class GetPortfolioController
{
    public function __construct(private readonly FindPortfolio $findPortfolio) {}

    public function __invoke(Request $request, Response $response, $id): Response
    {
        $portfolio = ($this->findPortfolio)((int) $id);
        $response
            ->getBody()
            ->write(json_encode([
                'id' => $portfolio->getId(),
                'allocations' => array_map(function (Allocation $allocation) {
                    return [
                        'id' => $allocation->getId(),
                        'shares' => $allocation->getShares()
                    ];
                }, $portfolio->getAllocations())
            ]));
        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}

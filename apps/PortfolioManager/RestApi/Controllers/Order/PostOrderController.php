<?php

namespace Finizens\Apps\PortfolioManager\RestAPI\Controllers\Order;

use Finizens\PortfolioManagement\Order\Application\CreateOrder;
use Finizens\PortfolioManagement\Portfolio\Application\FindPortfolio;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class PostOrderController
{
    public function __construct(
        private readonly CreateOrder $createOrder,
        private readonly FindPortfolio $findPortfolio
    ) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        if (!isset($body['id'], $body['portfolio'], $body['allocation'], $body['type'], $body['shares'])) {
            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
        }

        ($this->findPortfolio)($body['portfolio']);
        ($this->createOrder)($body['id'], $body['portfolio'], $body['allocation'], $body['type'], $body['shares']);

        return $response
            ->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
        }
}
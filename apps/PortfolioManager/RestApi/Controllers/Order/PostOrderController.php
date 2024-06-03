<?php

namespace Finizens\Apps\PortfolioManager\RestAPI\Controllers\Order;

use Finizens\PortfolioManagement\Order\Application\CreateOrder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class PostOrderController
{
    public function __construct(private readonly CreateOrder $createOrder) {}

    public function __invoke(Request $request, Response $response): Response
    {
        return $response
            ->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
        }
}

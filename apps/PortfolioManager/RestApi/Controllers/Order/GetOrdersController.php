<?php

namespace Finizens\Apps\PortfolioManager\RestAPI\Controllers\Order;

use Finizens\PortfolioManagement\Order\Application\GetOrders;
use Finizens\PortfolioManagement\Order\Domain\Order;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class GetOrdersController
{
    public function __construct(
        private readonly GetOrders $getOrders
    ) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        if (!isset($params['portfolio']) || ($params['completed'] ?? '') !== 'false') {
            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
        }

        $response
            ->getBody()
            ->write(json_encode(
                array_map(
                    fn (Order $order) => [
                        'id'         => $order->getId(),
                        'portfolio'  => $order->getPortfolioId(),
                        'allocation' => $order->getAllocationId(),
                        'shares'     => $order->getShares(),
                        'type'       => $order->getType()->value
                    ],
                    ($this->getOrders)((int) $params['portfolio'])
                )
            ));
        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        }
}

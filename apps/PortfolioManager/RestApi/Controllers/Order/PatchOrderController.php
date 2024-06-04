<?php

namespace Finizens\Apps\PortfolioManager\RestAPI\Controllers\Order;

use Finizens\PortfolioManagement\Order\Application\CompleteOrder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class PatchOrderController
{
    public function __construct(
        private readonly CompleteOrder $completeOrder
    ) {}

    public function __invoke(Request $request, Response $response, $id): Response
    {
        ($this->completeOrder)((int) $id);

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        }
}

<?php

use Finizens\Apps\PortfolioManager\RestAPI\Controllers\Order\GetOrdersController;
use Finizens\Apps\PortfolioManager\RestAPI\Controllers\Order\PatchOrderController;
use Finizens\Apps\PortfolioManager\RestAPI\Controllers\Order\PostOrderController;
use Finizens\Apps\PortfolioManager\RestAPI\Controllers\Portfolio\GetPortfolioController;
use Finizens\Apps\PortfolioManager\RestAPI\Controllers\Portfolio\PutPortfolioController;
use Finizens\Apps\PortfolioManager\RestAPI\ErrorHandlers\DefaultJsonErrorHandler;
use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../../../../vendor/autoload.php';
require __DIR__ . '/../config/container.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(false, false, false);
$errorMiddleware->setDefaultErrorHandler(DefaultJsonErrorHandler::class);

$routeCollector = $app->getRouteCollector();
$routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

$app->group('/api', function (RouteCollectorProxy $group) {
    $group->group('/portfolios/{id}', function (RouteCollectorProxy $group) {
        $group->get('', GetPortfolioController::class);
        $group->put('', PutPortfolioController::class);
    });

    $group->group('/orders', function (RouteCollectorProxy $group) {
        $group->get('', GetOrdersController::class);
        $group->post('', PostOrderController::class);
        $group->patch('/{id}', PatchOrderController::class);
    });
});

$app->run();

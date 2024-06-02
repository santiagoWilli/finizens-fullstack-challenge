<?php

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

$app->group('/api/portfolios/{id}', function (RouteCollectorProxy $group) {
    $group->get('', GetPortfolioController::class);
    $group->put('', PutPortfolioController::class);
});

$app->run();

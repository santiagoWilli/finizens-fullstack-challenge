<?php

use Finizens\Apps\PortfolioManager\RestAPI\Controllers\Portfolio\GetPortfolioController;
use Finizens\Apps\PortfolioManager\RestAPI\Controllers\Portfolio\PutPortfolioController;
use Finizens\Apps\PortfolioManager\RestAPI\ErrorHandlers\DefaultJsonErrorHandler;
use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestResponseArgs;

require __DIR__ . '/../../../../vendor/autoload.php';
require __DIR__ . '/../config/container.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(false, false, false);
$errorMiddleware->setDefaultErrorHandler(DefaultJsonErrorHandler::class);

$routeCollector = $app->getRouteCollector();
$routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

$app->get('/api/portfolios/{id}', GetPortfolioController::class);
$app->put('/api/portfolios/{id}', PutPortfolioController::class);

$app->run();

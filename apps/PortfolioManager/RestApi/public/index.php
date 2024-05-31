<?php

use Finizens\Apps\PortfolioManager\RestAPI\ErrorHandlers\DefaultJsonErrorHandler;
use Finizens\PortfolioManagement\Portfolio\Application\CreatePortfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\Allocation;
use Finizens\PortfolioManagement\Portfolio\Infrastructure\DummyPortfolioRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestResponseArgs;

require __DIR__ . '/../../../../vendor/autoload.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(false, false, false);
$errorMiddleware->setDefaultErrorHandler(DefaultJsonErrorHandler::class);

$routeCollector = $app->getRouteCollector();
$routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

$app->put('/api/portfolios/{id}', function (Request $request, Response $response, $id) {
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

    $createPortfolio = new CreatePortfolio(new DummyPortfolioRepository());
    $createPortfolio((int) $id, ...$allocations);

    return $response
        ->withStatus(200)
        ->withHeader('Content-Type', 'application/json');
});

$app->run();

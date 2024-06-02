<?php

use Finizens\Apps\PortfolioManager\RestAPI\ErrorHandlers\DefaultJsonErrorHandler;
use Finizens\PortfolioManagement\Portfolio\Application\CreatePortfolio;
use Finizens\PortfolioManagement\Portfolio\Domain\Allocation;
use Finizens\PortfolioManagement\Portfolio\Infrastructure\MySQLPortfolioRepository;
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

$db_host = getenv('DB_HOST');
$db_name = getenv('DB_NAME');
$dsn = "mysql:host=$db_host;dbname=$db_name";
$username = getenv('DB_USER');
$password = trim(file_get_contents(getenv('PASSWORD_FILE_PATH')));

$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$app->put('/api/portfolios/{id}', function (Request $request, Response $response, $id) use ($pdo) {
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

    $createPortfolio = new CreatePortfolio(new MySQLPortfolioRepository($pdo));
    $createPortfolio((int) $id, ...$allocations);

    return $response
        ->withStatus(200)
        ->withHeader('Content-Type', 'application/json');
});

$app->run();

<?php

declare(strict_types=1);

namespace Finizens\Apps\PortfolioManager\RestAPI\ErrorHandlers;

use Finizens\PortfolioManagement\Order\Domain\Exceptions\AllocationNotFound;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\SharesAreNotPositive;
use Finizens\PortfolioManagement\Order\Domain\Exceptions\UnknownOrderType;
use Finizens\PortfolioManagement\Portfolio\Domain\Exceptions\PortfolioNotFound;
use Finizens\PortfolioManagement\Portfolio\Domain\Exceptions\SharesAreNegative;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Response as Psr7Response;
use Throwable;

final class DefaultJsonErrorHandler implements ErrorHandlerInterface
{
    public function __invoke(
        Request $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $response = new Psr7Response();
        switch (get_class($exception)) {
            case HttpMethodNotAllowedException::class:
                $statusCode = 405;
                break;
            case PortfolioNotFound::class:
            case AllocationNotFound::class:
                $statusCode = 404;
                break;
            case UnknownOrderType::class:
            case SharesAreNegative::class:
            case SharesAreNotPositive::class:
                $statusCode = 400;
                break;
            default:
                $statusCode = 500;
                $response->getBody()->write(json_encode([
                    'error' => [
                        'message' => $exception->getMessage() ?: 'Internal Server Error'
                    ]
                ]));
                break;
        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}

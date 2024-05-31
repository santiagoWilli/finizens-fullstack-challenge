<?php

declare(strict_types=1);

namespace Finizens\Apps\PortfolioManager\RestAPI\ErrorHandlers;

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
        $statusCode = 500;
        if ($exception instanceof HttpMethodNotAllowedException) {
            $statusCode = 405;
        } else {
            $response->getBody()->write(json_encode([
                'error' => [
                    'message' => $exception->getMessage() ?: 'Internal Server Error'
                ]
            ]));
        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}

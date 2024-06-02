<?php

use DI\Container;
use Finizens\PortfolioManagement\Portfolio\Domain\PortfolioRepository;
use Finizens\PortfolioManagement\Portfolio\Infrastructure\MySQLPortfolioRepository;
use Slim\Factory\AppFactory;

$container = new Container();

$container->set(PortfolioRepository::class, function () {
    $db_host = getenv('DB_HOST');
    $db_name = getenv('DB_NAME');
    $dsn = "mysql:host=$db_host;dbname=$db_name";
    $username = getenv('DB_USER');
    $password = trim(file_get_contents(getenv('PASSWORD_FILE_PATH')));

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return new MySQLPortfolioRepository($pdo);
});

AppFactory::setContainer($container);

<?php

use DI\Container;
use Finizens\PortfolioManagement\Order\Domain\AllocationRepository;
use Finizens\PortfolioManagement\Order\Domain\Events\OrderCompleted;
use Finizens\PortfolioManagement\Order\Domain\OrderRepository;
use Finizens\PortfolioManagement\Order\Infrastructure\MySQLAllocationRepository;
use Finizens\PortfolioManagement\Order\Infrastructure\MySQLOrderRepository;
use Finizens\PortfolioManagement\Portfolio\Application\UpdatePortfolioUponOrderCompleted;
use Finizens\PortfolioManagement\Portfolio\Domain\PortfolioRepository;
use Finizens\PortfolioManagement\Portfolio\Infrastructure\MySQLPortfolioRepository;
use Finizens\Shared\Domain\Event\EventDispatcher;
use Finizens\Shared\Infrastructure\Event\InMemoryEventDispatcher;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;

$container = new Container();

$container->set(PDO::class, function () {
    $db_host = getenv('DB_HOST');
    $db_name = getenv('DB_NAME');
    $dsn = "mysql:host=$db_host;dbname=$db_name";
    $username = getenv('DB_USER');
    $password = trim(file_get_contents(getenv('PASSWORD_FILE_PATH')));

    return new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
});

$container->set(PortfolioRepository::class, function (ContainerInterface $container) {
    return new MySQLPortfolioRepository($container->get(PDO::class));
});

$container->set(OrderRepository::class, function (ContainerInterface $container) {
    return new MySQLOrderRepository($container->get(PDO::class));
});

$container->set(AllocationRepository::class, function (ContainerInterface $container) {
    return new MySQLAllocationRepository($container->get(PDO::class));
});

$container->set(EventDispatcher::class, function (ContainerInterface $container) {
    $eventDispatcher = new InMemoryEventDispatcher();
    $eventDispatcher->addListener(OrderCompleted::class, $container->get(UpdatePortfolioUponOrderCompleted::class));
    return $eventDispatcher;
});

AppFactory::setContainer($container);

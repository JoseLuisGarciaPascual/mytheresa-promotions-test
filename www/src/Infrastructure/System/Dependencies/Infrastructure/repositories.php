<?php

$container[\Doctrine\DBAL\Connection::class] = function (\Slim\Container $container) {
    $config = new \Doctrine\DBAL\Configuration();
    $dbConfig = $container->get('settings')['db'];

    $connectionParams = array(
        'dbname' => $dbConfig['dbname'],
        'user' => $dbConfig['user'],
        'password' => $dbConfig['pass'],
        'host' => $dbConfig['host'],
        'port' => $dbConfig['port'],
        'driver' => $dbConfig['driver'],
    );

    $connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

    return $connection;
};

$container[\PromoTest\Domains\Promotions\Repositories\ProductsRepositoryInterface::class] = function (\Slim\Container $container) {
    return new \PromoTest\Infrastructure\Promotions\Model\ProductsRepository(
        $container->get(\Doctrine\DBAL\Connection::class)
    );
};

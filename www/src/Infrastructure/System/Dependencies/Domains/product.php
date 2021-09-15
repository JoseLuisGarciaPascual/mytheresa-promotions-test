<?php

$container[\PromoTest\Domains\Promotions\UseCases\GetProductsWithDiscountUseCase::class] = function (\Slim\Container $container) {
    return new \PromoTest\Domains\Promotions\UseCases\GetProductsWithDiscountUseCase(
        $container->get(\PromoTest\Domains\Promotions\Repositories\ProductsRepositoryInterface::class)
    );
};

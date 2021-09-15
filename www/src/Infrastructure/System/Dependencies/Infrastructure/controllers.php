<?php

$container[\PromoTest\Infrastructure\Promotions\Controllers\ProductController::class] = function (\Slim\Container $container) {
    return new \PromoTest\Infrastructure\Promotions\Controllers\ProductController(
        $container->get(\PromoTest\Domains\Promotions\UseCases\GetProductsWithDiscountUseCase::class),
    );
};
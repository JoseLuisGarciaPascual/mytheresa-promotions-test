<?php

$app->group('/products', function () use ($app) {
    $app->get('', \PromoTest\Infrastructure\Promotions\Controllers\ProductController::class);
});
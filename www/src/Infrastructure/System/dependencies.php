<?php

$container = $app->getContainer();

// Infrastructure Dependencies

require __DIR__ . '/Dependencies/Infrastructure/controllers.php';
require __DIR__ . '/Dependencies/Infrastructure/repositories.php';

// Domain Dependencies

require __DIR__ . '/Dependencies/Domains/product.php';

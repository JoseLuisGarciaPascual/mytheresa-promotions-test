<?php
require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
$settings = require __DIR__ . '/../src/Infrastructure/System/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/Infrastructure/System/dependencies.php';

// Register routes
require __DIR__ . '/../src/Infrastructure/System/routes.php';

// Run app
$app->run();

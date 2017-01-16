<?php

require __DIR__ . '/../vendor/autoload.php';

use \Slim\App;

session_start();

// Instantiate the app
$config = require __DIR__ . '/../src/config/config.php';

$app = new App();

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();

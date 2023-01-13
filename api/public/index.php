<?php

declare(strict_types = 1);
error_reporting(1);
ini_set("display_errors", '1');

use Slim\App;
use App\Lib\Misc\Container;

require __DIR__ . '/../vendor/autoload.php';

$container = Container::getInstance();

require __DIR__ . '/../app/dependencies.php';

// Create Slim instance
$app = $container->get(App::class);

// Register routes
(require __DIR__ . '/../app/routes.php')($app);

// Register middleware
(require __DIR__ . '/../app/middleware.php')($app);

// Run the app
$app->run();
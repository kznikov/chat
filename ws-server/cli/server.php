<?php

declare(strict_types = 1);
error_reporting(1);
ini_set("display_errors", '1');

use App\Lib\Misc\Container;

require __DIR__ . '/../vendor/autoload.php';

$container = Container::getInstance();

require __DIR__ . '/../app/dependencies.php';

(new App\Server($container))->run();
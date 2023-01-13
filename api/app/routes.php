<?php

declare(strict_types = 1);

use Slim\App;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\CorsMiddleware;
use App\Controllers\LoginController;
use Slim\Routing\RouteCollectorProxy;
use App\Middlewares\BaseAuthMiddleware;
use App\Controllers\RegisterController;
use App\Controllers\ChatHistoryController;
use App\Enums\RequestMethods;

return function (App $app) {

    $app->group('/v1', function (RouteCollectorProxy $app) {
        $app->group('/user', function (RouteCollectorProxy $app) {
            $app->map([RequestMethods::HTTP_POST->value, RequestMethods::HTTP_OPTIONS->value], '/login', LoginController::class);
            $app->map([RequestMethods::HTTP_POST->value, RequestMethods::HTTP_OPTIONS->value], '/register', RegisterController::class);
        });

        $app->group('/chat', function (RouteCollectorProxy $app) {
            $app->map([RequestMethods::HTTP_POST->value, RequestMethods::HTTP_OPTIONS->value], '/history', ChatHistoryController::class);
        })->add(AuthMiddleware::class);
    })->add(CorsMiddleware::class);
};


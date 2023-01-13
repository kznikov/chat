<?php

declare(strict_types = 1);

use Slim\App;
use Slim\Psr7\Response;
use App\Lib\Misc\Payload;
use App\Enums\StatusCodes;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpMethodNotAllowedException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

return function (App $app) {
    $log = $app->getContainer()->get('log');

    // Log the requests/responses
    $app->add(function (Request $request, RequestHandler $handler) use ($log) {
        $routeContext = RouteContext::fromRequest($request);
        $route        = $routeContext->getRoute();

        $log->info("New {$request->getMethod()} request to {$route->getPattern()}; Params: " . json_encode($request->getParsedBody()));

        $response = $handler->handle($request);

        $log->info("Response: {$response->getBody()}");

        return $response;
    });

    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

    // Handle some Http exceptions
    $app->add(function (Request $request, RequestHandler $handler) use ($log) {
        try {
            return $handler->handle($request);
        } catch (HttpNotFoundException $httpException) {
            $response = new Response();
            $payload  = new Payload(StatusCodes::HTTP_404);

            $response->getBody()->write(json_encode($payload));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus($payload->getStatusCode()->value);
        } catch (HttpMethodNotAllowedException $httpException) {
            $response = new Response();
            $payload  = new Payload(StatusCodes::HTTP_405);

            $response->getBody()->write(json_encode($payload));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus($payload->getStatusCode()->value);
        }
    });

    // Add Error Middleware
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setDefaultErrorHandler(function (Request $request, Throwable $exception) use ($log) {
        $log->error("File: {$exception->getFile()}; Message: {$exception->getMessage()}; Line: {$exception->getLine()}");

        $response = new Response();
        $payload  = new Payload(StatusCodes::HTTP_500);
        $response->getBody()->write(json_encode($payload));

        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($payload->getStatusCode()->value);
    });
};
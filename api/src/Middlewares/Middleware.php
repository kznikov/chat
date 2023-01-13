<?php

declare(strict_types = 1);

namespace App\Middlewares;

use App\Lib\Misc\Payload;
use App\Enums\StatusCodes;
use App\Lib\Misc\Container;
use Slim\Psr7\Response as Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

abstract class Middleware
{
    protected Response $response;

    protected array $settings;

    public function __construct(protected Container $container)
    {
        $this->response = new Response();
        $this->settings = $this->container->get('settings');
    }

    abstract public function __invoke(Request $request, RequestHandler $handler): Response;

    protected function respondWithData(array $data = [], StatusCodes $statusCode = StatusCodes::HTTP_200, array $headers = []): ResponseInterface
    {
        $payload = new Payload($statusCode, $data);

        return $this->respond($payload, $headers);
    }

    private function respond(Payload $payload, array $headers): ResponseInterface
    {
        $json = json_encode(
            $payload,
            JSON_UNESCAPED_SLASHES |
            JSON_UNESCAPED_UNICODE
        );

        $this->response->getBody()->write($json);

        foreach ($headers as $header => $value){
            $this->response = $this->response->withAddedHeader($header, $value);
        }


        return $this->response->withStatus($payload->getStatusCode()->value);
    }
}
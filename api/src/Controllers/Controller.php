<?php

declare(strict_types = 1);

namespace App\Controllers;

use Monolog\Logger;
use Slim\Psr7\Response;
use App\Lib\Misc\Helper;
use App\Lib\Misc\Payload;
use App\Enums\StatusCodes;
use App\Enums\HTTPStatusCodes;
use App\Repositories\Repository;
use App\Factories\RepositoryFactory;
use Psr\Container\ContainerInterface;
use App\Repositories\LoginRepository;
use Psr\Http\Message\ResponseInterface;
use App\Repositories\RegisterRepository;
use App\Repositories\ChatHistoryRepository;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class Controller
{

    protected Logger $log;

    protected Request $request;

    protected Response $response;

    protected Repository $repository;

    public function __construct(protected ContainerInterface $container)
    {
        $this->log        = $this->container->get('log');
        $this->repository = $this->getRepository();
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $this->request  = $request;
        $this->response = $response;

        return $this->handle();
    }

    abstract protected function handle(): ResponseInterface;

    protected function getRequestData(): array
    {
        return $this->request->getParsedBody() +
            $this->request->getUploadedFiles();
    }

    protected function respondWithData(array $data = [], StatusCodes $statusCode = StatusCodes::HTTP_200): ResponseInterface
    {
        $payload = new Payload($statusCode, $data);

        return $this->respond($payload);
    }

    protected function getRepository(?string $repo = null): ?Repository
    {
        return match ($repo ?? get_class($this)) {
            LoginController::class => RepositoryFactory::create(
                LoginRepository::class,
                [$this->container->get('mysql'),]
            ),
            RegisterController::class => RepositoryFactory::create(
                RegisterRepository::class,
                [$this->container->get('mysql')]
            ),
            ChatHistoryController::class => RepositoryFactory::create(
                ChatHistoryRepository::class,
                [$this->container->get('mysql')]
            ),
            default => null,
        };
    }

    protected function respond(Payload $payload): ResponseInterface
    {
        $json = json_encode(
            $payload,
            JSON_UNESCAPED_SLASHES |
            JSON_UNESCAPED_UNICODE
        );

        $this->response->getBody()->write($json);

        $origin = $this->request->getHeader('Origin')[0];

        return $this->response
            ->withHeader('Access-Control-Allow-Origin',Helper::checkOrigin($origin, $this->container->get('settings')['cors_domains']) )
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, Secret')
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Cache-Control', 'no-store')
            ->withStatus($payload->getStatusCode()->value);
    }

}
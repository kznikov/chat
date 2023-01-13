<?php

declare(strict_types = 1);

namespace App\Middlewares;

use Firebase\JWT\JWT;
use Slim\Psr7\Response;
use App\Lib\Misc\Payload;
use App\Enums\StatusCodes;
use App\Lib\Misc\Container;
use App\Interfaces\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware extends Middleware
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $authHeader = $request->getHeader('Authorization');

        $token = @explode(' ', $authHeader[0])[1];

        try {
            $decodedToken = JWT::decode($token, $this->settings['jwt']['secret'], ['HS256']);

            if ($this->isExpired($decodedToken->exp)) {
                $this->respondWithData(['text' => 'Token has expired'], StatusCodes::HTTP_401);
            }
        } catch (\Exception $exception) {
            return $this->respondWithData([], StatusCodes::HTTP_401, [
                'Content-Type' => 'application/json',
                'Cache-Control'=> 'no-store'
            ]);
        }

        return $handler->handle($request);
    }

    private function isExpired(int $expiredTime): bool
    {
        return $expiredTime < time();
    }

}
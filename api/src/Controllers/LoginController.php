<?php

declare(strict_types = 1);

namespace App\Controllers;

use Firebase\JWT\JWT;
use App\Enums\StatusCodes;
use App\Lib\Misc\Constants;
use Psr\Http\Message\ResponseInterface;

class LoginController extends Controller
{

    protected function handle(): ResponseInterface
    {
        $requestData = $this->getRequestData();

        // Validate input
        if (
            !isset($requestData['email']) ||
            !isset($requestData['password'])
        ) {
            return $this->respondWithData(
                ['text' => 'Missing required request parameter/s'],
                StatusCodes::HTTP_400
            );
        }

        if (!filter_var($requestData['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->respondWithData(
                ['text' => 'Invalid email'],
                StatusCodes::HTTP_400
            );
        }

        $user = $this->repository->getUserByEmail($requestData['email']);

        if (
            isset($user['password']) &&
            password_verify($requestData['password'], $user['password'])
        ) {
            $data = ['token' => $this->generateToken($user)];
            $code = StatusCodes::HTTP_200;
        } else {
            $data = ['text' => 'Access denied'];
            $code = StatusCodes::HTTP_401;
        }

        return $this->respondWithData($data, $code);
    }

    private function generateToken(array $data): string
    {
        $secret = $this->container->get('settings')['jwt']['secret'];

        $iat = time();

        $token = JWT::encode([
            'iat'  => $iat,
            'iss'  => 'localhost',
            'exp'  => $iat + Constants::JWT_TOKEN_EXPIRE,
            "data" => [
                "id"         => $data['id'],
                "first_name" => $data['first_name'],
                "last_name"  => $data['last_name'],
                "email"      => $data['email'],
            ],
        ], $secret);

        // Update database
        $this->repository->updateUserToken($data['id'], $token);

        return $token;
    }

}
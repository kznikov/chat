<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Lib\Misc\Helper;
use App\Enums\StatusCodes;
use App\Lib\Misc\Constants;
use Psr\Http\Message\ResponseInterface;

class RegisterController extends Controller
{

    protected function handle(): ResponseInterface
    {
        $requestData = $this->getRequestData();

        // Validate input
        if (
            !isset($requestData['email']) ||
            !isset($requestData['password']) ||
            !isset($requestData['last_name']) ||
            !isset($requestData['first_name'])
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

        if (
            $this->getRepository(LoginController::class)
                ->getUserByEmail($requestData['email'])
        ) {
            return $this->respondWithData(
                ['text' => 'Email already exists'],
                StatusCodes::HTTP_400
            );
        }

        if (strlen($requestData['password']) < Constants::MIN_PASSWORD_LENGTH) {
            return $this->respondWithData(
                ['text' => 'Password is too short'],
                StatusCodes::HTTP_400
            );
        }

        $record = [
            'id'         => Helper::generateGUID(),
            'first_name' => $requestData['first_name'],
            'last_name'  => $requestData['last_name'],
            'email'      => $requestData['email'],
            'password'   => password_hash($requestData['password'], PASSWORD_BCRYPT),
        ];

        if (!$this->repository->addUser($record)) {
            return $this->respondWithData(
                ['text' => 'Failed to register new user'],
                StatusCodes::HTTP_500
            );
        }

        return $this->respondWithData();
    }

}
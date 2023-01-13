<?php

declare(strict_types = 1);

namespace App\Enums;

enum ReasonPhrases: string
{

    case HTTP_200 = 'OK';
    case HTTP_400 = 'Bad Request';
    case HTTP_401 = 'Unauthorized';
    case HTTP_404 = 'Route Not Found';
    case HTTP_405 = 'Method Not Allowed';
    case HTTP_500 = 'Error Processing Request';

    public static function fromStatusCode(StatusCodes $statusCode): ?ReasonPhrases
    {
        return current(
            array_filter(self::cases(), fn($elem) => $elem->name === $statusCode->name)
        );
    }

}
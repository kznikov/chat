<?php

declare(strict_types = 1);

namespace App\Enums;

enum StatusCodes: int
{

    case HTTP_200 = 200;
    case HTTP_400 = 400;
    case HTTP_401 = 401;
    case HTTP_404 = 404;
    case HTTP_405 = 405;
    case HTTP_500 = 500;

}
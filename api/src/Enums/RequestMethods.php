<?php

declare(strict_types = 1);

namespace App\Enums;

enum RequestMethods:string
{
    case HTTP_POST = 'POST';
    case HTTP_OPTIONS = 'OPTIONS';

}
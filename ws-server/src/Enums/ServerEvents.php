<?php

declare(strict_types = 1);

namespace App\Enums;

enum ServerEvents: string
{

    case Start = 'start';
    case Open = 'open';
    case Close = 'close';
    case Message = 'message';

}
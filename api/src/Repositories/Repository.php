<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Lib\Resources\MySQL;

abstract class Repository
{

    public function __construct(protected MySQL $db) { }

}
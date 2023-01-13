<?php

declare(strict_types = 1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;

class ChatHistoryController extends Controller
{

    protected function handle(): ResponseInterface
    {
        // TODO: Implement handle() method.
        return $this->respondWithData();
    }

}
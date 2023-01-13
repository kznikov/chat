<?php

declare(strict_types = 1);

namespace App\Controllers;

use Swoole\Http\Request;
use App\Exceptions\RedisException;
use Swoole\WebSocket\Server as SwooleServer;

class OpenEventController extends BaseController
{

    public function __invoke(SwooleServer $server, Request $request)
    {
        // Just log the connection identifier
        $this->log->info("Newly opened connection with ID: $request->fd");
    }

}
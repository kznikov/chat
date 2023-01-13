<?php

declare(strict_types = 1);

namespace App\Controllers;

use Swoole\WebSocket\Server as SwooleServer;

class CloseEventController extends BaseController
{

    public function __invoke(SwooleServer $server, int $fd): bool
    {
        $this->log->info("Close connection with ID: $fd");

        // Clear cached connection form Redis
        return (bool) $this->redis->del($fd);
    }

}
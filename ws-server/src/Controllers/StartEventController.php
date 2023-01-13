<?php

declare(strict_types = 1);

namespace App\Controllers;

use Swoole\WebSocket\Server as SwooleServer;

class StartEventController extends BaseController
{

    public function __invoke(SwooleServer $server)
    {
        $this->log->info("WebSocket Server is started at http://{$this->settings['server']['host']}:{$this->settings['server']['port']}");
    }

}
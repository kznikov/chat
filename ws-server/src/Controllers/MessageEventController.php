<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Lib\Misc\Helper;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server as SwooleServer;

class MessageEventController extends BaseController
{

    public function __invoke(SwooleServer $server, Frame $frame)
    {
        $this->log->info("Received new message: " . json_encode($frame->data));

        $data = json_decode($frame->data, true);

        $server->push(
            $frame->fd,
            json_encode([
                "id"   => Helper::generateGUID(),
                "type" => $data['type'],
                "text" => $data['text'] ?? '',
                "from" => "Krasi",
                "to"   => "Krasimir",
            ])
        );
    }

}
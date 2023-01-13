<?php

declare(strict_types = 1);

namespace App;

use Error;
use Throwable;
use Monolog\Logger;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use App\Lib\Misc\Container;
use App\Enums\ServerEvents;
use App\Exceptions\RedisException;
use App\Exceptions\ServerException;
use App\Controllers\OpenEventController;
use App\Controllers\CloseEventController;
use App\Controllers\StartEventController;
use Swoole\WebSocket\Server as SwooleServer;
use App\Controllers\MessageEventController;
use App\Controllers\HandshakeEventController;

class Server
{

    private Logger $log;

    private array $settings;

    private SwooleServer $server;

    public function __construct(private Container $container)
    {
        $this->settings = $this->container->get('settings')['server'];

        if (!isset($this->settings['host'])) {
            throw new ServerException('Host is not set!');
        }

        if (!isset($this->settings['port'])) {
            throw new ServerException('Port is not set!');
        }

        $this->log = $this->container->get('log');

    }

    public function run(): void
    {
        try {
            $this->server = new SwooleServer(
                $this->settings['host'],
                $this->settings['port']
            );

            $this->server->on(ServerEvents::Open->value, new OpenEventController($this->container));
            $this->server->on(ServerEvents::Close->value, new CloseEventController($this->container));
            $this->server->on(ServerEvents::Start->value, new StartEventController($this->container));
            $this->server->on(ServerEvents::Message->value, new MessageEventController($this->container));

            $this->server->start();
        } catch (Throwable $e) {
            $this->log->critical("Exception at file {$e->getFile()}; Line {$e->getLine()}; Message {$e->getMessage()}");
        }
    }

}
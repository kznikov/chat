<?php

declare(strict_types = 1);

namespace App\Lib\Resources;

use Monolog\Logger;
use App\Lib\Misc\Container;
use App\Exceptions\RedisException;

class Redis
{

    private static Redis|null $instance = null;

    private \Redis $redis;

    private Logger $log;

    private string $namespace;

    private function __construct(?array $config = [])
    {
        $this->log = Container::getInstance()->get('log');

        try {
            $this->redis = new \Redis();

            $this->redis->connect(
                $config['host'],
                (int)$config['port']
            );
            $this->redis->auth($config['password']);
        } catch (\RedisException $exception) {
            $this->log->critical('No connection to redis! ' . $exception->getMessage());
        }
    }

    public static function getInstance(?array $config = []): Redis
    {
        if (!self::$instance) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function __call(string $method, array $arguments = []): mixed
    {
        try {
            if (isset($this->namespace) && !empty($arguments)) {
                $arguments[0] = $this->namespace . ':' . $arguments[0];
            }

            return call_user_func_array([$this->redis, $method], $arguments);
        } catch (\RedisException $exception) {
            $this->log->critical('Redis exception! ' . $exception->getMessage());
        }

        return false;
    }

}
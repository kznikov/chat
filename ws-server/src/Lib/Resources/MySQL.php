<?php

declare(strict_types = 1);

namespace App\Lib\Resources;

use PDO;
use PDOException;
use Monolog\Logger;
use App\Lib\Misc\Container;
use App\Exceptions\MySQLException;

class MySQL
{

    private static MySQL|null $instance = null;

    private PDO $pdo;

    private Logger $log;

    private function __construct(?array $config = [])
    {
        $this->log = Container::getInstance()->get('log');

        try {
            $this->pdo = new PDO(
                'mysql:host=' . $config['host'] . ';dbname=' . $config['database'],
                $config['username'],
                $config['password']
            );
        } catch (PDOException $exception) {
            $this->log->critical('No connection to database! ' . $exception->getMessage());
        }
    }

    public static function getInstance(?array $config = []): MySQL
    {
        if (!self::$instance) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    public function __call(string $method, array $arguments = []): mixed
    {
        try {
            return call_user_func_array([$this->pdo, $method]);
        } catch (PDOException $exception) {
            $this->log->critical('MySQL exception! ' . $exception->getMessage());
        }

        return false;
    }

}
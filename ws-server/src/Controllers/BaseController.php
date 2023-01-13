<?php

declare(strict_types = 1);

namespace App\Controllers;

use Monolog\Logger;
use App\Lib\Misc\Container;
use App\Lib\Resources\Redis;

abstract class BaseController
{

    protected Redis $redis;

    protected readonly Logger $log;

    protected array $settings;

    public function __construct(protected readonly Container $container)
    {
        $this->log = $this->container->get('log');
        $this->redis = $this->container->get('redis');
        $this->settings = $this->container->get('settings');
    }

}
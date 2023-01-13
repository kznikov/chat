<?php

declare(strict_types = 1);

namespace App\Interfaces;

interface FactoryInterface
{

    public static function create(string $class, array $arguments = []): object;

}
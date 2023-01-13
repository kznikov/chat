<?php

declare(strict_types = 1);

namespace App\Factories;

use Exception;
use App\Repositories\Repository;
use App\Interfaces\FactoryInterface;

final class RepositoryFactory implements FactoryInterface
{

    const NAMESPACE = 'App\Repositories';

    public static function create(string $class, array $arguments = []): Repository
    {
        $class = str_contains($class, self::NAMESPACE) ? $class : self::NAMESPACE . '\\' . $class;

        if (!class_exists($class)) {
            throw new Exception("Undefined class $class");
        }

        return new $class(...$arguments);
    }

}
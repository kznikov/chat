<?php

declare(strict_types = 1);

namespace App\Lib\Misc;

abstract class Helper
{

    public static function generateGUID(): string
    {
        mt_srand((int)microtime() * 10000);
        $host   = gethostname();
        $charid = md5(uniqid($host . rand(), true));

        return substr($charid, 0, 8) . '-' .
            substr($charid, 8, 4) . '-' .
            substr($charid, 12, 4) . '-' .
            substr($charid, 16, 4) . '-' .
            substr($charid, 20, 12);
    }

}
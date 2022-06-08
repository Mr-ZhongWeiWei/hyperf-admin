<?php

namespace App\Common\Utils;

use Hyperf\Utils\ApplicationContext;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Cache
 * @package App\Common\Utils
 */
class Cache
{
    public static function getInstance()
    {
        return ApplicationContext::getContainer()->get(CacheInterface::class);
    }

    public static function __callStatic($name, $arguments)
    {
        return self::getInstance()->$name(...$arguments);
    }
}

<?php

namespace App\Common\Utils;

use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;

/**
 * Class Log
 * @package App\Common\Utils
 */
class Log
{
    public static function getInstance(string $name = 'app', string $group = 'db')
    {
        return ApplicationContext::getContainer()->get(LoggerFactory::class)->get($name,$group);
    }

    public static function __callStatic($name, $arguments)
    {
        self::getInstance()->$name(...$arguments);
    }
}

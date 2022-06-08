<?php

namespace App\Common\Factory;

use App\Common\Utils\Log;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Hyperf\Framework\Logger\StdoutLogger;
/**
 * Class StdoutLoggerFactory
 * @package App
 */
class StdoutLoggerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return env('APP_ENV') == 'dev' ? ApplicationContext::getContainer()->get(StdoutLogger::class) : Log::getInstance('sys','default');
    }
}

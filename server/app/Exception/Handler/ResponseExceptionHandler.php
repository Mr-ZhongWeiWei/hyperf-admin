<?php

namespace App\Exception\Handler;

use App\Exception\ResponseException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;
/**
 * Class ResponseExceptionHandler
 * @package App\Exception\Handler
 */
class ResponseExceptionHandler extends ExceptionHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof ResponseException){
            // 阻止异常冒泡
            $this->stopPropagation();
            return $response->withStatus(200)->withBody(new SwooleStream($throwable->getMessage()));
        }
        return $response;
    }

    /**
     * @inheritDoc
     */
    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}

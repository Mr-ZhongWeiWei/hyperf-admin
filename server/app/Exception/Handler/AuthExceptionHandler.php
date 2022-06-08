<?php

namespace App\Exception\Handler;

use App\Common\Traits\ApiResponse;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Qbhy\HyperfAuth\Exception\AuthException;
use Qbhy\SimpleJwt\Exceptions\JWTException;
use Throwable;

/**
 * Class AuthExceptionHandler
 * @package App\Exception\Handler
 */
class AuthExceptionHandler extends ExceptionHandler
{
    use ApiResponse;

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof AuthException || $throwable instanceof JWTException){
            // 阻止异常冒泡
            $this->stopPropagation();
            return $response->withStatus(401)->withBody(new SwooleStream($this->error($throwable->getMessage(), $this->tokenExpiredCode, false)));
        }

        return $response;
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}

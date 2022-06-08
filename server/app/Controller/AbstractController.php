<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Common\Traits\ApiResponse;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
use Qbhy\HyperfAuth\AuthManager;

abstract class AbstractController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @Inject
     * @var AuthManager
     */
    protected $auth;

    use ApiResponse;

    /**
     * 获取客户端IP
     * @return mixed|string
     * @author: Zhong Weiwei
     * @Date: 21:24  2022/4/14
     */
    protected function clientRealIP()
    {
        $headers = $this->request->getHeaders();

        if(isset($headers['x-forwarded-for'][0]) && !empty($headers['x-forwarded-for'][0])) {
            return $headers['x-forwarded-for'][0];
        } elseif (isset($headers['x-real-ip'][0]) && !empty($headers['x-real-ip'][0])) {
            return $headers['x-real-ip'][0];
        }

        $serverParams = $this->request->getServerParams();

        return $serverParams['remote_addr'] ?? '';
    }

    /**
     * 获取请求域名
     * @return string
     * @author: Zhong Weiwei
     * @Date: 21:44  2022/4/14
     */
    protected function getDomain()
    {
        $domain =   $this->request->getUri()->getScheme().'://'.$this->request->getUri()->getHost();
        if ($this->request->getUri()->getPort()){
            $domain .=  ':'.$this->request->getUri()->getPort();
        }
        return $domain;
    }
}

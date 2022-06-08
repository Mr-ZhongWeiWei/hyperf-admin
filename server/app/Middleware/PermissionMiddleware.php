<?php

namespace App\Middleware;

use App\Common\Annotation\CollectAccess;
use App\Common\Traits\ApiResponse;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qbhy\HyperfAuth\AuthManager;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Container\ContainerInterface;
/**
 * Class PermissionMiddleware
 * @package App\Middleware
 */
class PermissionMiddleware implements MiddlewareInterface
{

    use ApiResponse;
    /**
     * @Inject
     * @var AuthManager
     */
    protected $auth;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $permission =   [];
        foreach (AnnotationCollector::getMethodsByAnnotation(CollectAccess::class) as $item){
            $callback   =   $item['class'].'@'.$item['method'];
            if ($callback   ==  $request->getAttribute(Dispatched::class)->handler->callback){
                $permission =   [
                    'rule'  =>  $item['annotation']->rule,
                    'logic' =>  $item['annotation']->logic
                ];
            }
        }

        if ($permission){
            if (is_string($permission['rule'])){
                $permission['rule']   =   explode(',',$permission['rule']);
            }

            $intersect  =   array_intersect($permission['rule'],$this->auth->guard()->user()->permission);
            if (!$intersect || $permission['logic'] == 'and' && count($intersect) != count($permission['rule'])){
                return $this->response->withStatus(403)->withBody(new SwooleStream($this->error('您没有权限！', $this->NoAccessCode, false)));
            }
        }
        return $handler->handle($request);
    }
}

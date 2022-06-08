<?php

namespace App\Middleware;

use App\Common\Utils\Cache;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
/**
 * Class LoadConfigMiddleware
 * @package App\Middleware
 */
class LoadConfigMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     * @var \Hyperf\Contract\ConfigInterface
     */
    public $configInterface;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (Cache::get('DB_EXTEND_CONFIGS')){
            $configs    =   Cache::get('DB_EXTEND_CONFIGS');
        }else{
            $configs    =   Db::table('config')->get()->toArray();
            Cache::set('DB_EXTEND_CONFIGS',$configs);
        }
        foreach ($configs as $item){
            if ($item->type == 'line'){
                continue;
            }
            switch ($item->type){
                case 'checkbox':
                    $item->value = json_decode($item->value, true);
                    break;
                case 'singleimg':
                    $item->value = json_decode($item->value, true)[0]['url'];
                    break;
                case 'multipleimg':
                    $urls   =   [];
                    foreach (json_decode($item->value, true) as $value){
                        $urls[] =   $value['url'];
                    }
                    $item->value    =   $urls;
                    break;
            }
            $item->type   ==  'checkbox'  &&  $item->value = explode(',',$item->value);
            $this->configInterface->set($item->name,$item->value);
        }
        return $handler->handle($request);
    }
}

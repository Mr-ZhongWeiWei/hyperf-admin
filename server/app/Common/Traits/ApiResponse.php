<?php

namespace App\Common\Traits;

use App\Exception\ResponseException;

/**
 * Class ApiResponse
 * @package App\Common
 */
trait ApiResponse
{
    private $successCode        =   200;
    private $errorCode          =   204;
    private $tokenExpiredCode   =   401;
    private $tokenInvalidCode   =   203;
    private $NoAccessCode       =   403;

    /**
     * 请求成功响应
     * @param array $data
     * @param string $message
     * @param null $code
     * @author: Zhong Weiwei
     * @Date: 21:22  2022/3/16
     */
    public function success($data = [], $message = 'success', $code = null)
    {
        throw new ResponseException(json_encode([
            'code'      =>  $code ?? $this->successCode,
            'message'   =>  $message,
            'data'      =>  $data
        ], JSON_UNESCAPED_UNICODE));
    }

    /**
     * 请求异常返回
     * @param string $message
     * @param null $code
     * @param true $isException
     * @author: Zhong Weiwei
     * @Date: 21:23  2022/3/16
     */
    public function error($message = 'error',  $code = null, $isException = true)
    {
        $data   =   json_encode([
            'code'      =>  $code ?? $this->errorCode,
            'message'   =>  $message,
        ], JSON_UNESCAPED_UNICODE);

        if ($isException === false){
            return $data;
        }else{
            throw new ResponseException($data);
        }
    }

    /**
     * 分页返回
     * @param array $data
     * @param $totalCount
     * @param $pageIndex
     * @param $pageSize
     * @param string $message
     * @author: Zhong Weiwei
     * @Date: 20:43  2022/3/29
     */
    public function listData($data = [], $totalCount, $pageIndex, $pageSize, $message = 'success')
    {
        throw new ResponseException(json_encode([
            'code'      =>  $code ?? $this->successCode,
            'message'   =>  $message,
            'data'      =>  $data,
            'pageInfo' => [
                'count' => $totalCount,
                'current' => (int)$pageIndex,
                'limit' => $pageSize,
            ]
        ], JSON_UNESCAPED_UNICODE));
    }
}

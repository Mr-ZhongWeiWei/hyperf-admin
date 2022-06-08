<?php
/**
 * Created by PhpStorm.
 * User: Zhong Weiwei
 * Date: 2022/3/13
 * Time: 15:57
 */

if (!function_exists('loadRoutes')){
    /**
     * 加载路由文件
     * @author: Zhong Weiwei
     * @Date: 16:01  2022/3/13
     */
    function loadRoutes()
    {
        $path = BASE_PATH . '/routes';
        $dirs = scandir($path);
        foreach ($dirs as $dir) {
            if ($dir != '.' && $dir != '..') {
                $routeFilePath = $path . "/{$dir}";
                if (is_file($routeFilePath)){
                    require_once $routeFilePath;
                }
            }
        }
    }
}

if (!function_exists('is_mobile')){

    /**
     * 验证手机号
     * @param $mobile
     * @return bool
     * @author: Zhong Weiwei
     * @Date: 21:44  2022/4/7
     */
    function is_mobile($mobile)
    {
        if (preg_match('/^1[3456789]\d{9}$/', $mobile)){
            return  true;
        }else{
            return false;
        }
    }
}
if (!function_exists('listToTree')){
    /**
     * 转换成树形结构
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int $root
     * @return array
     * @author: Zhong Weiwei
     * @Date: 16:15  2022/3/19
     */
    function listToTree($list, $pk = 'id', $pid = 'parent_id', $child = 'children', $root = 0)
    {
        // 创建Tree
        $tree = [];
        if (is_array($list)) {
            $refer = [];
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}

if(!function_exists('curl_http')){
    /**
     * @param $url
     * @param string $data
     * @param string $method
     * @param array $header
     * @return bool|string
     * @author: Zhong Weiwei
     * @Date: 20:42  2022/4/17
     */
    function curl_http($url, $data = '', $method = 'get', $header = [])
    {
        $ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;

        $ch = curl_init();
        $headers = ['Accept-Charset: utf-8'];
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, $header));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible;MSIE 5.01;Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        if (strtoupper($method) == "POST") {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
}






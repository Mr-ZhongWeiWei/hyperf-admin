<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Common\Traits\UploadHandle;
/**
 * Class Upload
 * @package App\Controller\Admin
 */
class UploadController extends AbstractController
{

    use UploadHandle;

    /**
     * 上传单个文件
     * @throws \League\Flysystem\FilesystemException
     * @author: Zhong Weiwei
     * @Date: 20:26  2022/4/13
     */
    public function uploadFile()
    {
        $file = $this->request->file('file');
        $path   =   $this->putFile('uploads', $file);
        $this->success($this->getDomain().$path,'上传成功');
    }

}

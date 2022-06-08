<?php

namespace App\Common\Traits;

use Hyperf\Filesystem\FilesystemFactory;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Upload\UploadedFile;
/**
 * Class UploadHandle
 * @package App\Common\Traits
 */
trait UploadHandle
{

    /**
     * @Inject
     * @var FilesystemFactory
     */
    protected $filesystem;
    /**
     * 获取微秒
     * @return float
     * @author: Zhong Weiwei
     * @Date: 21:17  2022/4/12
     */
    protected function getMillisecond()
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * 获取重命名后的文件路径
     * @param string $file
     * @param string $rule
     * @return string
     * @author: Zhong Weiwei
     * @Date: 21:39  2022/4/12
     */
    protected function getRenameFilePath(UploadedFile $file, string $rule = 'date'): string
    {
        switch ($rule){
            case 'date':
                $renameFile =   date('Ymd').'/'.md5($this->getMillisecond().uniqid()).'.'.$file->getExtension();
                break;
            case 'md5':
                $renameFile =   date('Ymd').'/'.md5_file($file->getPathname()).'.'.$file->getExtension();
                break;
            case 'sha1':
                $renameFile =   date('Ymd').'/'.sha1_file($file->getPathname()).'.'.$file->getExtension();
                break;
        }
        return $renameFile;
    }

    /**
     * 上传文件
     * @param string $path
     * @param UploadedFile $file
     * @param string $adapterName
     * @param string $rule
     * @return string
     * @throws \League\Flysystem\FilesystemException
     * @author: Zhong Weiwei
     * @Date: 20:27  2022/4/13
     */
    protected function putFile(string $path, UploadedFile $file, string $adapterName = 'local', string $rule = 'date')
    {
        $stream = fopen($file->getRealPath(), 'r+');
        $fileName   =   $this->getRenameFilePath($file, $rule);
        $this->filesystem->get($adapterName)->writeStream($path.'/'.$fileName, $stream);
        fclose($stream);
        return "/$path/$fileName";
    }
}

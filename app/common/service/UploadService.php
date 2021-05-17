<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 17:31
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\service;


use app\common\utils\Result;
use app\common\utils\upload\UploadBase;

class UploadService
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 17:32
     * @Description: 上传图片
     */
    public function image($files): array
    {
        $obj      = UploadBase::getUploadObj();
        $imageArr = $obj->upload($files, true);
        return Result::serviceSucc($imageArr);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 17:32
     * @Description: 上传文件
     */
    public function file($files): array
    {
        $obj     = UploadBase::getUploadObj();
        $fileArr = $obj->upload($files, false);
        return Result::serviceSucc($fileArr);
    }

}
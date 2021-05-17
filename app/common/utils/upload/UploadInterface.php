<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 16:57
 * @Description: 上传文件接口
 * 
 * @return 

 */

namespace app\common\utils\upload;


interface UploadInterface
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 16:58
     * @Description: 上传文件组
     * @param $files :文件域的字段名组
     * @param bool $isImg : 上传的是否是图片
     * @return mixed
     */
    public function upload($files, bool $isImg = false): array;
}















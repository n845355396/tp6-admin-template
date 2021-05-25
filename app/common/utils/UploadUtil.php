<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/18 9:44
 * @Description: 上传文件工具类
 * 
 * @return 

 */

namespace app\common\utils;


use Error;
use LogicException;
use think\facade\Config;

class UploadUtil
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 9:46
     * @Description: 获取当前存储对象
     * @return mixed
     */
    public static function getUploadObj()
    {
        try {
            $uploadConfig = Config::get('upload');
            $type         = $uploadConfig['type'];
            $className    = $uploadConfig['stores'][$type]['class_name'];
            return new $className();

        } catch (Error $e) {
            throw new LogicException($e->getMessage());
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 10:03
     * @Description: 上传文件
     * @param $files
     * @param false $isImg
     * @return array
     */
    public static function upload($files, bool $isImg = false): array
    {
        $obj      = self::getUploadObj();
        return $obj->upload($files, $isImg);
    }
}
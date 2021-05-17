<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 16:59
 * @Description: 上传文件基类
 * 
 * @return 

 */

namespace app\common\utils\upload;


use Error;
use Exception;
use LogicException;
use think\facade\Config;

abstract class UploadBase
{
    //上传文件配置项
    protected $config;

    public function __construct()
    {
        $this->setConfig();
    }

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
     * @return mixed
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 17:03
     * @Description: 赋值文件配置项
     * @return void
     */
    protected function setConfig()
    {
        try {

            $uploadConfig = Config::get('upload');
            $this->config = Config::get('upload.stores.' . $uploadConfig['type']);

            if (!$this->getConfig()) {
                throw new \LogicException('配置项不存在');
            }

        } catch (Exception | Error $e) {
            throw new \LogicException($e->getMessage());
        }
    }


}













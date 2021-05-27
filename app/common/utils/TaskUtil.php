<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 16:07
 * @Description: 支付工具类
 * 
 * @return 

 */

namespace app\common\utils;


use app\common\utils\payment\vo\AliPayParamsVo;
use app\common\utils\queue\QueueParamsDto;
use Error;
use LogicException;
use think\facade\Config;

class TaskUtil
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 9:46
     * @Description: 获取任务队列对象
     * @return mixed
     */
    public static function getTaskObj()
    {
        try {
            $taskConfig = Config::get("sys_task");
            $type       = $taskConfig['type'];
            $config     = $taskConfig['stores'][$type];
            $className  = $config['product_class_name'];
            //            $obj->setConfig($config);
            return new $className();

        } catch (Error $e) {
            throw new LogicException($e->getMessage());
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/25 11:25
     * @Description: 添加任务到队列
     * @param QueueParamsDto $dto 数据对象
     * @param int $delayTime 延时执行；单位:秒
     * @return mixed
     */
    public static function publish(QueueParamsDto $dto, int $delayTime = 0)
    {
        $taskObj = self::getTaskObj();
        if (empty($delayTime) || $delayTime <= 0) {
            return $taskObj->push($dto);
        } else {
            return $taskObj->delay($dto, $delayTime);
        }
    }

}






















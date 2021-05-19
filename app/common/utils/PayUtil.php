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
use Error;
use LogicException;
use think\facade\Config;

class PayUtil
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 9:46
     * @Description: 获取支付对象
     * @return mixed
     */
    public static function getPayObj($payType)
    {
        try {
            $payConfig = Config::get("pay.{$payType}");
            $className = $payConfig['class_name'];
            return new $className();

        } catch (Error $e) {
            throw new LogicException($e->getMessage());
        }
    }
}






















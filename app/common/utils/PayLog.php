<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/20 10:39
 * @Description: 支付日志工具类
 * 
 * @return 

 */

namespace app\common\utils;


use think\facade\App;
use think\facade\Log;

class PayLog
{
    //保存日志类目 start
    const ALI_PAY_DIR = 'ali';
    const WX_PAY_DIR = 'wx';
    //保存日志目录 end


    /**
     * @Author: lpc
     * @DateTime: 2021/5/20 10:49
     * @Description: 写入支付日志
     * @param string $payType
     * @param string $payName
     * @param string $title
     * @param array $data
     */
    public static function write(string $payType, string $payName, string $title, array $data)
    {
        $path = App::getRuntimePath() . '/pay_log/' . $payType . '/' . $payName;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $path    = str_replace('//', '/', $path . '/' . date('Y_m_d', time()) . '.log');
        $content = '【' . date('Y-m-d H:i:s', time()) . '】'
            . "\r\n" . $title . "\r\n" . var_export($data, true) . "\r\n\r\n";
        file_put_contents($path, $content, FILE_APPEND);
    }
}

























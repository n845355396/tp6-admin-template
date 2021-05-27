<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/26 18:21
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\sms;


interface SmsInterface
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/26 18:24
     * @Description: 使用平台提供code模式发送短信
     * @param $mobile :手机号
     * @param $code :平台提供的code
     * @param $data :平台需要的数据
     * @return array
     */
    public function codeSendMode($mobile, $code, $data): array;

    /**
     * @Author: lpc
     * @DateTime: 2021/5/26 18:25
     * @Description: 自定义短信内容模式发送短信
     * @param $mobile :手机号
     * @param $content :短信内容
     * @return array
     */
    public function diySendMode($mobile, $content): array;
}
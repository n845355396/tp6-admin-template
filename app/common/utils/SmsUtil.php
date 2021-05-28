<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/26 18:43
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils;


use Error;
use Exception;
use think\facade\Cache;
use think\facade\Config;

class SmsUtil
{
    const DIY_MODE = 'diy';//自定义短信模式
    const CODE_MODE = 'code';//平台提供code短信模式

    //模板常量
    const TMP_VERIFICATION_CODE = 'verification_code';
    const TMP_TEST_SMS = "test_sms";

    public static function getSmsObj()
    {
        try {
            $taskConfig = Config::get("sms");
            $type       = $taskConfig['type'];
            $config     = $taskConfig['stores'][$type];
            $className  = $config['class_name'];
            return new $className();

        } catch (Error $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/26 18:45
     * @Description: 发送短信
     * @param $mobile :手机号
     * @param $template
     * @param $data :数据
     * @param string $mode :模式
     * @return mixed
     * @throws Exception
     */
    public static function send($mobile, $template, $data, string $mode = self::DIY_MODE)
    {
        $obj = self::getSmsObj();
        if ($mode == self::DIY_MODE) {
            $contentRes = $obj->templateToContent($template, $data);
            if (!$contentRes['status']) {
                return $contentRes;
            }
            $content = $contentRes['data']['content'];

            $res = $obj->diySendMode($mobile, $content);
        } else {
            $codeRes = $obj->templateToCode($template);
            if (!$codeRes['status']) {
                return $codeRes;
            }
            $res = $obj->codeSendMode($mobile, $codeRes['data']['code'], $data);
        }
        return $res;
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/26 18:57
     * @Description: 生成短信验证码
     * @param $mobile
     * @return int
     */
    public static function generateVerificationCode($mobile): int
    {
        $code = mt_rand(1000, 9999);
        //生成缓存，过期时间60秒
        Cache::set('sms_code_' . $mobile, $code, 3600);
        return $code;
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/26 18:57
     * @Description: 检查短信验证码正确性
     * @param $mobile
     * @param $code
     * @return bool
     */
    public static function checkCode($mobile, $code): bool
    {
        $cacheCode = Cache::pull('sms_code_' . $mobile);
        if ($code == null) {
            return false;
        }
        return $code == $cacheCode;
    }
}











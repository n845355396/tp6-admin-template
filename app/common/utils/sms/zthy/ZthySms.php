<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/26 18:41
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\sms\zthy;


use app\common\utils\sms\SmsBase;
use app\common\utils\sms\SmsInterface;

class ZthySms extends SmsBase implements SmsInterface
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
    public function codeSendMode($mobile, $code, $data): array
    {
        //模板信息发送demo
        $url     = "http://api.mix2.zthysms.com/v2/sendSmsTp";
        $records = [];
        $record  = [
            "mobile"    => $mobile,
            "tpContent" => $data
        ];
        array_push($records, $record);

        $tKey     = time();
        $password = md5(md5($this->curSmsConf['password']) . $tKey);
        $smsData  = [
            'tpId'      => $code, //模板id
            'username'  => $this->curSmsConf['username'], //用户名
            'password'  => $password, //密码
            'tKey'      => $tKey, //tKey
            'signature' => $this->smsConfig['sms_prefix'],
            'records'   => $records
        ];
        $ret      = $this->httpPost($url, $smsData);
        $ret      = json_decode($ret, true);
        if ($ret['code'] == 200 && $ret['msg'] == 'success') {
            return self::serviceSucc($ret);
        }
        return self::serviceError('发送失败', $ret);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/26 18:25
     * @Description: 自定义短信内容模式发送短信
     * @param $mobile :手机号
     * @param $content :短信内容
     * @return array
     */
    public function diySendMode($mobile, $content): array
    {
        //模板信息发送demo
        $url      = "https://api.mix2.zthysms.com/v2/sendSms";
        $tKey     = time();
        $password = md5(md5($this->curSmsConf['password']) . $tKey);
        $data     = [
            'username' => $this->curSmsConf['username'], //用户名
            'password' => $password, //密码
            'tKey'     => $tKey, //tKey
            'mobile'   => $mobile, //手机号码
            'content'  => $content
        ];
        $ret      = $this->httpPost($url, $data);
        $ret      = json_decode($ret, true);
        if ($ret['code'] == 200 && $ret['msg'] == 'success') {
            return self::serviceSucc($ret);
        }
        return self::serviceError('发送失败', $ret);
    }


}

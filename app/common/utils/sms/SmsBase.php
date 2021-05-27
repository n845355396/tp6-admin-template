<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/26 18:20
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\sms;


use app\common\utils\Result;
use think\facade\Config;

class SmsBase extends Result
{
    //sms文件整个配置
    protected $smsConfig;
    //当前sms平台配置
    protected $curSmsConf;
    //短信模板数组
    protected $smsTemplateArr;

    public function __construct()
    {
        $this->smsConfig      = Config::get('sms');
        $this->curSmsConf     = $this->smsConfig['stores'][$this->smsConfig['type']];
        $this->smsTemplateArr = $this->smsConfig['sms_template'];
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/27 9:47
     * @Description: 短信模板替换成短信code
     * @param $template
     * @return array
     */
    public function templateToCode($template): array
    {
        if (empty($this->smsTemplateArr[$template]['platform_code'])) {
            return self::serviceError('短信模板缺失!');
        }
        $code = $this->smsTemplateArr[$template]['platform_code'];
        return self::serviceSucc(['code' => $code]);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/26 18:28
     * @Description: 将模板替换成短信内容
     * @param $template
     * @param $data
     * @return array
     */
    public function templateToContent($template, $data): array
    {
        if (empty($this->smsTemplateArr[$template])) {
            return self::serviceError('短信模板缺失!');
        }
        $content = $this->smsTemplateArr[$template]['local_diy'];

        foreach ($data as $key => $value) {
            $content = str_replace("{#" . $key . "}", $value, $content);
        }
        $content = $this->smsConfig['sms_prefix'] . $content;

        return self::serviceSucc(['content' => $content]);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/27 10:15
     * @Description: post请求，扩展内不一致可重写此方法
     * @param $url
     * @param $date
     * @return bool|string
     */
    protected function httpPost($url, $date)
    { // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
//        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_POST, true); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($date)); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, false); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_HEADER, false); //开启header
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8'
        )); //类型为json
        $result = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Error POST' . curl_error($curl);
        }
        curl_close($curl); // 关键CURL会话
        return $result; // 返回数据
    }

}
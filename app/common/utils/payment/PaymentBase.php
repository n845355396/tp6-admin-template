<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 15:19
 * @Description: 第三方支付基类
 * 
 * @return 

 */

namespace app\common\utils\payment;


use think\facade\Config;

class PaymentBase
{
    public function __construct()
    {
        $this->setConfig();
        $this->notifyUrl = getDomainUrl('api/payment/notify/' . $this->payName);
    }

    /**
     * 第三方支付代号
     * @var
     */
    protected $payName;

    /**
     * 异步回调地址
     * @var string
     */
    protected $notifyUrl = "";

    /**
     * 第三方支付配置文件
     * @var
     */
    protected $config;

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     */
    public function setConfig(): void
    {
        $type         = $this->payName;
        $this->config = Config::get("pay.{$type}");

    }

}














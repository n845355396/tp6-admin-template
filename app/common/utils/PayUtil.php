<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 16:07
 * @Description: 支付工具类
 * 
 * @return 

 */

namespace app\common\utils;


use app\common\utils\payment\PaymentInterface;
use Error;
use Exception;
use LogicException;
use think\facade\Config;

class PayUtil
{

    private $payObj;

    public function __construct($payType)
    {
        $this->getPayObj($payType);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 9:46
     * @Description: 获取支付对象
     * @param $payType
     * @return mixed
     * @throws Exception
     */
    public function getPayObj($payType)
    {
        try {
            $payConfig = Config::get("pay.{$payType}");
            $className = $payConfig['class_name'];

            $this->payObj = new $className();

            return $this->payObj;

        } catch (Error $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:26
     * @Description: 第三方支付发起付款
     * @param array $payParams
     * @return mixed
     * @throws Exception
     */
    public function payment(array $payParams)
    {
        return $this->payObj->payment($payParams);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:27
     * @Description: 第三支付异步回调接收
     * @return mixed
     */
    public function callBack()
    {
        return $this->payObj->callBack();
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:30
     * @Description: 第三方支付退款
     * @param array $refundParams
     * @param array $optional
     * @return mixed
     */
    public function refund(array $refundParams, array $optional = [])
    {
        return $this->payObj->refund();
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:33
     * @Description: 第三方转账到所属第三方账户
     * @param array $transferParams
     * @return mixed
     */
    public function transfer(array $transferParams)
    {
        return $this->payObj->transfer();
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:34
     * @Description: 第三方转账到个人用户银行卡
     * @param array $transferParams
     * @return mixed
     */
    public function transferToBank(array $transferParams)
    {
        return $this->payObj->transferToBank();
    }
}






















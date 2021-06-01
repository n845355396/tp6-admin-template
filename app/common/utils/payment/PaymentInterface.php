<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 15:18
 * @Description: 第三方支付接口
 * 
 * @return 

 */

namespace app\common\utils\payment;


interface PaymentInterface
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:26
     * @Description: 第三方支付发起付款
     * @param array $payParams
     * @return mixed
     */
    public function payment(array $payParams);

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:27
     * @Description: 第三支付异步回调接收
     * @return mixed
     */
    public function callBack();

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:30
     * @Description: 第三方支付退款
     * @param array $refundParams
     * @param array $optional
     * @return mixed
     */
    public function refund(array $refundParams, array $optional = []);


    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:33
     * @Description: 第三方转账到所属第三方账户
     * @param array $transferParams
     * @return mixed
     */
    public function transfer(array $transferParams);

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:34
     * @Description: 第三方转账到个人用户银行卡
     * @param array $transferParams
     * @return mixed
     */
    public function transferToBank(array $transferParams);
}





















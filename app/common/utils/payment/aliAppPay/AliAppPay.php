<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 15:46
 * @Description: 支付宝app支付类
 * 
 * @return 

 */

namespace app\common\utils\payment\aliAppPay;


use app\common\utils\payment\aliAppPay\aop\AopClient;
use app\common\utils\payment\aliAppPay\aop\request\AlipayTradeAppPayRequest;
use app\common\utils\payment\PaymentBase;
use app\common\utils\payment\PaymentInterface;
use Exception;

class AliAppPay extends PaymentBase implements PaymentInterface
{
    protected $payName = 'ali_app_pay';
    /**
     * 支付宝支付网关
     * @var string
     */
    private $gatewayUrl = 'https://openapi.alipay.com/gateway.do';

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:26
     * @Description: 第三方支付发起付款
     * @param $payParams
     * @return mixed
     */
    public function payment($payParams)
    {
        $aop                     = new AopClient ();
        $aop->gatewayUrl         = $this->gatewayUrl;
        $aop->appId              = $this->config['app_id'];
        $aop->rsaPrivateKey      = $this->config['rsa_private_key'];
        $aop->alipayrsaPublicKey = $this->config['alipay_rsa_public_key'];
        $aop->apiVersion         = '1.0';
        $aop->signType           = 'RSA2';
        $aop->postCharset        = 'UTF-8';
        $aop->format             = 'json';
        $request                 = new AlipayTradeAppPayRequest ();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $bizParams = [
            "body"            => $payParams['body'],//大概就是简介之类的吧
            "subject"         => $payParams['subject'],//订单标题
            "out_trade_no"    => $payParams['out_trade_no'],//商户网站唯一订单号
            //绝对超时时间，格式为yyyy-MM-dd HH:mm:ss,此处支付宝文档不一致，案例是timeout_express，参数是time_expire
            "timeout_express" => "30m",
            "total_amount"    => $payParams['total_amount'],//价格
            "product_code"    => "QUICK_MSECURITY_PAY",//销售产品码，商家和支付宝签约的产品码,可选

        ];
        $request->setNotifyUrl($this->notifyUrl);
        $request->setBizContent(json_encode($bizParams));
        //这里和普通的接口调用不同，使用的是sdkExecute
        //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
        // echo htmlspecialchars($response);//就是orderString 可以直接给客户端请求，无需再做处理。

        //@Author: lpc @Description: 这里客户端端就是接收这样的一个字符串，不用自作聪明去转数组给前端 @DateTime: 2021/5/19 16:40
        return $aop->sdkExecute($request);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:27
     * @Description: 第三支付异步回调接收
     * @return mixed
     */
    public function callBack()
    {
        try {
            $params = $_POST;
            $aop    = new AopClient;
            //'请填写支付宝公钥，一行字符串';
            $aop->alipayrsaPublicKey = $this->config['alipay_rsa_public_key'];
            $res                     = $aop->rsaCheckV1($params, NULL, "RSA2");
            if (!$res) {
                throw  new Exception("验证失败");
            }
            return ['res' => 'success'];
        } catch (\Exception $e) {
            return ['res' => 'failure', 'msg' => $e->getMessage()];
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:30
     * @Description: 第三方支付退款
     * @param $refundParams
     * @return mixed
     */
    public function refund($refundParams)
    {
        // TODO: Implement refund() method.
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:33
     * @Description: 第三方转账到所属第三方账户
     * @param $transferParams
     * @return mixed
     */
    public function transfer($transferParams)
    {
        // TODO: Implement transfer() method.
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:34
     * @Description: 第三方转账到个人用户银行卡
     * @param $transferParams
     * @return mixed
     */
    public function transferToBank($transferParams)
    {
        // TODO: Implement transferToBank() method.
    }
}
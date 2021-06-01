<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 15:46
 * @Description: 支付宝app支付类
 * 
 * @return 

 */

namespace app\common\utils\payment\aliAppPay;


use app\common\utils\PayLog;
use app\common\utils\payment\aliAppPay\aop\AopClient;
use app\common\utils\payment\aliAppPay\aop\request\AlipayTradeAppPayRequest;
use app\common\utils\payment\aliAppPay\aop\request\AlipayTradeRefundRequest;
use app\common\utils\payment\PaymentBase;
use app\common\utils\payment\PaymentInterface;
use Exception;
use LogicException;
use think\facade\Config;
use think\facade\Log;

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
     * @param array $payParams
     * @return string
     */
    public function payment(array $payParams): array
    {
        try {


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

            $this->log(PayLog::ALI_PAY_DIR,
                '发起支付数据===》',
                ['request_params' => $payParams, 'result' => $request]
            );

            //@Author: lpc @Description: 这里客户端端就是接收这样的一个字符串，不用自作聪明去转数组给前端 @DateTime: 2021/5/19 16:40
            $paymentData = $aop->sdkExecute($request);
            return self::serviceSucc(['payment_data' => $paymentData], '获取成功');
        } catch (Exception $e) {
            return self::serviceError($e->getMessage());
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:27
     * @Description: 第三支付异步回调接收
     * @return array|string[]
     */
    public function callBack(): array
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
            $this->log(PayLog::ALI_PAY_DIR,
                '支付异步回调===》通过',
                ['request_params' => $params, 'result' => '"验证通过']
            );

            return self::serviceSucc(['res' => 'success'], '验证通过');
        } catch (Exception $e) {
            $this->log(PayLog::ALI_PAY_DIR,
                '支付异步回调===》失败',
                ['request_params' => $params, 'result' => $e->getMessage()]
            );
            return self::serviceError($e->getMessage(), ['res' => 'failure']);
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 15:30
     * @Description: 第三方支付退款
     * @param array $refundParams
     * @param array $optional
     * @return array
     */
    public function refund(array $refundParams, array $optional = []): array
    {
        try {
            if (empty($refundParams['out_trade_no']) && empty($refundParams['trade_no'])) {
                throw new LogicException("商户订单号与支付宝交易号必须得存在一个！");
            }

            if (empty($refundParams['refund_amount']) ||
                !is_numeric($refundParams['refund_amount']) ||
                $refundParams['refund_amount'] < 0.01
            ) {
                throw new LogicException("退款金额不能小于0.01元！");
            }

            $refundData = [
                //商户订单号
                "out_trade_no"    => !empty($refundParams['out_trade_no']) ? $refundParams['out_trade_no'] : '',
                //支付宝交易号
                "trade_no"        => !empty($refundParams['trade_no']) ? $refundParams['trade_no'] : '',
                //需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数
                "refund_amount"   => $refundParams['refund_amount'],
                //订单退款币种
                "refund_currency" => !empty($optional['refund_currency']) ? $optional['refund_currency'] : 'CNY',
                //退款原因说明，商家自定义。
                "refund_reason"   => !empty($optional['refund_reason']) ? $optional['refund_reason'] : "正常退款",
                //标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传。
                "out_request_no"  => !empty($optional['out_request_no']) ? $optional['out_request_no'] : uniqid(),
            ];

            $aop                     = new AopClient ();
            $aop->gatewayUrl         = $this->gatewayUrl;
            $aop->appId              = $this->config['app_id'];
            $aop->rsaPrivateKey      = $this->config['rsa_private_key'];//'请填写开发者私钥去头去尾去回车，一行字符串';
            $aop->alipayrsaPublicKey = $this->config['alipay_rsa_public_key'];//'请填写支付宝公钥，一行字符串';
            $aop->apiVersion         = '1.0';
            $aop->signType           = 'RSA2';
            $aop->postCharset        = 'UTF-8';
            $aop->format             = 'json';
            $request                 = new AlipayTradeRefundRequest();
            $request->setBizContent(json_encode($refundData));
            $result = $aop->execute($request);

            $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";

            $resultCode = $result->$responseNode->code;
            $resultMsg  = $result->$responseNode->msg;

            $resData = [
                'status' => false,
                'msg'    => "退款失败！"
            ];

            if (!empty($resultCode) && $resultCode == 10000) {
                $resData['status'] = true;
                $resData['msg']    = "退款成功";
            } else {
                $resData['msg'] = $resultMsg;
            }
            $this->log(PayLog::ALI_PAY_DIR,
                '订单退款===》' . $resData['msg'],
                ['refund_params' => $refundData, 'result' => $resData]
            );
            if ($resData['status']) {
                return self::serviceSucc([], $resData['msg']);
            }
            return self::serviceError($resData['msg']);

        } catch (Exception $e) {
            $this->log(PayLog::ALI_PAY_DIR,
                '订单退款===》失败',
                ['refund_params' => $refundParams, 'optional_params' => $optional, 'result' => $e->getMessage()]
            );

            return self::serviceError($e->getMessage());
        }
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
        // TODO: Implement transfer() method.
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
        // TODO: Implement transferToBank() method.
    }
}
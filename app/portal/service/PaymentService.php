<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 17:01
 * @Description: Description
 * 
 * @return 

 */

namespace app\portal\service;


use app\common\facade\RedisFacade;
use app\common\service\Kernel;
use app\common\utils\payment\vo\AliPayParamsVo;
use app\common\utils\PayUtil;
use app\common\utils\RedisUtil;
use app\common\utils\Result;
use Exception;
use LogicException;
use think\cache\driver\Redis;
use think\facade\Cache;

class PaymentService extends BaseService
{

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 18:19
     * @Description: 支付异步回调
     */
    public function notify($payType)
    {
        //获取第三方支付对象
        $payObj  = Kernel::single(PayUtil::class, [$payType]);
        $resData = $payObj->callBack();

        if (!$resData['status']) {
            return $resData['data']['res'];
        }
        //todo lpc 第三方的验证通过，下面是系统内部状态修改
        #code...
        return $resData['data']['res'];
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 17:03
     * @Description: app端发起的支付
     * @param $data
     * @return array
     */
    public function appPay($data): array
    {
        try {
            //参数验证
            if (empty($data['pay_type'])) {
                return Result::serviceError('支付方式不存在！');
            }

            //创建支付单
            $payOrderData = $this->createPaymentSlip($data);
            //获取第三方支付对象
            $payObj = Kernel::single(PayUtil::class, [$data['pay_type']]);
            //lpc 订单id，不是order_id请修改
            $orderId = $payOrderData['order_id'];
            //填充支付发起所需参数
            $params = $this->fillingPayParams($data['pay_type'], $orderId);
            $payRes = $payObj->payment($params);
            if (!$payRes['status']) {
                return Result::serviceError($payRes['msg']);
            }
            $resData['pay_data']   = $payRes['data']['payment_data'];
            $resData['order_data'] = $payOrderData;
            return Result::serviceSucc($resData);
        } catch (Exception $e) {
            return Result::serviceError($e->getMessage());
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 17:09
     * @Description: 支付调起，创建支付单
     * @param $data
     * @return array
     */
    private function createPaymentSlip($data): array
    {
        //todo lpc 此处在实际业务中完成,返回订单号等数据
        try {
            $resData             = [];
            $resData['order_id'] = 1234567;
            return $resData;
        } catch (Exception $e) {
            throw new LogicException($e->getMessage());
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 17:22
     * @Description: 填充第三方发起支付所需参数
     * @param $payType
     * @param $orderId
     * @return array
     */
    private function fillingPayParams($payType, $orderId): array
    {
        //lpc 本着不污染第三方独立性，在此处做支付参数填充吧
        //todo 我觉得不是个好办法，因为如果要添加新第三方，此处要追加,先这样吧
        $payParams = null;
        switch ($payType) {
            case 'ali_app_pay':
                $payParams = $this->aliAppPayParams($orderId);
                break;
            case 'wx_app_pay':
                $payParams = $this->wxAppPayParams($orderId);
                break;
        }
        return objectToArray($payParams);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 17:29
     * @Description: 填充支付宝订单支付数据
     * @param $orderId : 系统订单id
     */
    private function aliAppPayParams($orderId): AliPayParamsVo
    {
        //todo 此处根据系统订单，一定能获取到对应数据的

        //模拟数据
        $vo = new AliPayParamsVo();
        $vo->setBody('我是body');
        $vo->setSubject('我是subject');
        $vo->setTotalAmount(12.36);
        $vo->setOutTradeNo($orderId);
        return $vo;
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 17:29
     * @Description: 填充支付宝订单支付数据
     * @param $orderId : 系统订单id
     */
    private function wxAppPayParams($orderId)
    {
        //todo 此处根据系统订单，一定能获取到对应数据的
        return null;
    }

}


















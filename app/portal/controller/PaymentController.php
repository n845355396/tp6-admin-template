<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 16:48
 * @Description: 支付控制器
 * 
 * @return 

 */

namespace app\portal\controller;


use app\common\service\Kernel;
use app\common\utils\PayUtil;
use app\common\utils\Result;
use app\portal\service\PaymentService;
use think\facade\Log;
use think\response\Json;

class PaymentController extends AuthController
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 16:55
     * @Description: app端异步支付发起
     */
    public function appPay(): Json
    {
        $data = $this->request->dataParams;
        $res  = Kernel::single(PaymentService::class)->appPay($data);
        return Result::disposeServiceRes($res);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 16:54
     * @Description: app端异步回调地址
     */
    public function notify($payType)
    {
        return Kernel::single(PaymentService::class)->notify($payType);
    }
}












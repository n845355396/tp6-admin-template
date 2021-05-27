<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/20 13:29
 * @Description: Description
 * 
 * @return 

 */

namespace app\portal\controller;


use app\common\service\Kernel;
use app\common\service\SmsService;
use app\common\service\TaskService;
use app\common\task\TaskBase;
use app\common\task\TestTask;
use app\common\utils\queue\QueueParamsDto;
use app\common\utils\queue\think_queue\ThinkProducer;
use app\common\utils\Result;
use app\common\utils\SmsUtil;
use think\response\Json;

class UserController extends AuthController
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/20 13:29
     * @Description: 获取用户信息
     */
    public function info(): Json
    {
//        $data = new QueueParamsDto();
//        $data->setData(['ts' => time(), 'bizId' => uniqid(), 'a' => 1]);
//        $data->setTaskClass(TestTask::class);
////        $data->setRoutes(['delay_route']);
//
//        $res = Kernel::single(TaskService::class)->publish($data, 10);

       $smsService = Kernel::single(SmsService::class);
       $res = $smsService->send(15526222933,SmsUtil::TMP_TEST_SMS,['name'=>'小明','result'=>"小明走队列"]);

        return Result::disposeServiceRes($res);
    }
}















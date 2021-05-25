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
use app\common\service\TaskService;
use app\common\task\TaskBase;
use app\common\task\TestTask;
use app\common\utils\queue\QueueParamsDto;
use app\common\utils\queue\think_queue\ThinkProducer;
use app\common\utils\Result;
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
        $data = new QueueParamsDto();
        $data->setData(['ts' => time(), 'bizId' => uniqid(), 'a' => 1]);
        $data->setTaskClass(TestTask::class);

        $res = Kernel::single(TaskService::class)->publish($data);

        return Result::disposeServiceRes($res);
    }
}















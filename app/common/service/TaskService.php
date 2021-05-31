<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/25 10:32
 * @Description: 队列服务类
 * 
 * @return 

 */

namespace app\common\service;


use app\common\model\TaskMdl;
use app\common\utils\queue\QueueParamsDto;
use app\common\utils\TaskUtil;

class TaskService extends BaseService
{
    #--------队列使用案例 start---------
//        $data = new QueueParamsDto();
//        $data->setData(['ts' => time(), 'bizId' => uniqid(), 'a' => 1]);
//        $data->setTaskClass(TestTask::class);
//        //lpc route主要在rabbit里用，queueName是tp自带的用，都有默认值
//        //$data->setRoutes(['cancel_order','notify']);
//        //$data->setQueueName('default_queue');
//
//        $res = Kernel::single(TaskService::class)->publish($data);//即时队列
//        $res = Kernel::single(TaskService::class)->publish($data,10);//延时队列,10秒后执行
    #--------队列使用案例 end---------

    /**
     * @var TaskMdl
     */
    private $taskMdl;

    public function __construct()
    {
        parent::__construct();
        $this->taskMdl = new TaskMdl();
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/25 11:25
     * @Description: 添加任务到队列
     * @param QueueParamsDto $dto 数据对象
     * @param int $delayTime 延时执行；单位:秒
     * @return mixed
     */
    public function publish(QueueParamsDto $dto, int $delayTime = 0)
    {
        $res = TaskUtil::publish($dto, $delayTime);
        if ($res['status']) {
            TaskMdl::log($dto);
        }
        return $res;
    }

    public function list($where, $pageData): array
    {
        return $this->taskMdl->list($where, $pageData);
    }
}













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
use app\common\task\TestTask;
use app\common\utils\queue\QueueParamsDto;
use app\common\utils\TaskUtil;

class TaskService extends BaseService
{

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
}













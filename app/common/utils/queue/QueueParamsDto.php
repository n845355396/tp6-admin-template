<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/24 16:36
 * @Description: 队列执行参数bean
 * 
 * @return 

 */

namespace app\common\utils\queue;


class QueueParamsDto
{
    /**
     * 执行具体任务的类
     * @var
     */
    private $task_name;
    /**
     * 任务数据
     * @var
     */
    private $data;
    /**
     * 任务归属的队列名称
     * @var
     */
    private $queue_name;

    /**
     * @return mixed
     */
    public function getTaskName()
    {
        return $this->task_name;
    }

    /**
     * @param mixed $task_name
     */
    public function setTaskName($task_name): void
    {
        $this->task_name = $task_name;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getQueueName()
    {
        return $this->queue_name;
    }

    /**
     * @param mixed $queue_name
     */
    public function setQueueName($queue_name): void
    {
        $this->queue_name = $queue_name;
    }

}
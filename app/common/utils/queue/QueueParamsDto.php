<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/24 16:36
 * @Description: 队列执行参数bean
 * 
 * @return 

 */

namespace app\common\utils\queue;


use app\common\task\TaskBase;
use app\common\task\TaskInterface;
use JsonSerializable;
use think\facade\Config;

class QueueParamsDto implements JsonSerializable
{
    public function __construct()
    {
        $taskConfig        = Config::get("sys_task");
        $this->queue_name  = $taskConfig['queue_name'];
        $this->unique_code = uniqid();
    }

    /**
     * 队列唯一码
     * @var
     */
    private $unique_code;

    /**
     * 执行具体任务的类
     * @var
     */
    private $task_class;
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
     * @return string
     */
    public function getTaskClass(): string
    {
        return $this->task_class;
    }

    /**
     * @param String $task_name
     */
    public function setTaskClass(string $task_class): void
    {
        $this->task_class = $task_class;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getQueueName(): string
    {
        return $this->queue_name;
    }

    /**
     * 队列通道，不填则默认值
     * @param string $queue_name
     */
    public function setQueueName(string $queue_name): void
    {
        $this->queue_name = $queue_name;
    }

    /**
     * @return mixed
     */
    public function getUniqueCode()
    {
        return $this->unique_code;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key => $val) {
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }
}
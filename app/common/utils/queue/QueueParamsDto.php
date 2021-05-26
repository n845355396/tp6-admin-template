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
        $this->unique_code = md5(uniqid(mt_rand(), true));
        $this->routes      = [self::NORMAL_ROUTE];
    }

    #=======================队列通道命名start=================
    //默认队列
    const  DEFAULT_QUEUE = 'default_queue';
    //消息通知队列
    const NOTIFY_QUEUE = 'notify_queue';
    //订单取消队列
    const CANCEL_ORDER_QUEUE = 'cancel_order_queue';
    #=======================队列通道命名end===================


    #=======================route命名start=================
    //默认路由
    const  NORMAL_ROUTE = 'normal';
    #=======================route命名end===================


    #================参数选填区start======================
    //队列唯一码,
    private $unique_code;
    //任务归属的队列名称,有默认队列
    private $queue_name;
    //路由数组，rabbit可以用
    //这个路由是为了满足rabbitMq添加的，不需要的可忽略
    private $routes;
    #================参数选填区end======================

    #================参数必填区start======================
    //执行具体任务的类
    private $task_class;
    //任务数据
    private $data;
    #================参数必填区end======================


    #==========================GetSet================================
    /**
     * @return string
     */
    public function getTaskClass(): string
    {
        return $this->task_class;
    }

    /**
     * @param String $task_class
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

    /**
     * @return mixed
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param mixed $routes
     */
    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }
}
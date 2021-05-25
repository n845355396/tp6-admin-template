<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/24 15:48
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\queue;


use app\common\model\TaskMdl;
use app\common\utils\Result;
use think\facade\Config;

class QueueBase extends Result
{
    public function __construct()
    {
        $taskConfig          = Config::get("sys_task");
        $type                = $taskConfig['type'];
        $config              = $taskConfig['stores'][$type];
        $this->config        = $config;
        $this->sysTaskConfig = $taskConfig;
    }

    protected $sysTaskConfig;

    private $config;


    /**
     * 队列配置信息
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }


    /**
     * 队列配置信息
     */
    public function setConfig($config): void
    {
        $this->config = $config;
    }

    public function upLog(string $uniqueCode, string $result, int $retryNum = 0)
    {
        TaskMdl::upLog($uniqueCode, $result, $retryNum);
    }

}
















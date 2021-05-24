<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/24 15:48
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\queue;


class QueueBase
{
    public function __construct()
    {
        $this->setConfig();
    }

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
     * @param mixed $config
     */
    public function setConfig(): void
    {
        $this->config = [];
    }

}
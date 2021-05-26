<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/24 18:14
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\queue;


interface ConsumerInterface
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/24 18:15
     * @Description: 开启消费者
     * @param string $queueName : 开启监听队列名称
     * @return mixed
     */
    public function run(string $queueName = '');
}
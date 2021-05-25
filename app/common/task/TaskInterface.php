<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/24 17:51
 * @Description: 队列任务接口
 * 
 * @return 

 */

namespace app\common\task;


interface TaskInterface
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/24 17:54
     * @Description: 任务执行方法
     * @param $data : 执行数据
     * @return bool
     */
    public function handle($data): bool;
}
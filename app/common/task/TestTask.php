<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/24 17:56
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\task;


class TestTask extends TaskBase implements TaskInterface
{

    /**
     * @Author: lpc
     * @DateTime: 2021/5/24 17:54
     * @Description: 任务执行方法
     * @param $data : 执行数据
     * @return bool
     */
    public function handle($data): bool
    {
        // 根据消息中的数据进行实际的业务处理...

        print("我task执行了 \n");

        return true;
    }
}
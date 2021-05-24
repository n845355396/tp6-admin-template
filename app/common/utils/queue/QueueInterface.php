<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/24 15:48
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\queue;


interface QueueInterface
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/24 15:51
     * @Description: 立即执行队列
     * @param $dto: 队列数据对象
     * @return mixed
     */
    public function push($dto): array;

    /**
     * @Author: lpc
     * @DateTime: 2021/5/24 16:31
     * @Description: 延迟执行队列
     * @param $dto: 队列数据对象
     * @param $delayTime:延时执行时间；单位：秒
     * @return array
     */
    public function delay($dto, $delayTime): array;
}













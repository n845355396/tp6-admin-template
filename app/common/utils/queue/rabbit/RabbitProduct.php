<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/25 13:18
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\queue\rabbit;


use AMQPConnection;
use app\common\utils\queue\ProducerInterface;
use app\common\utils\queue\QueueBase;
use app\common\utils\queue\QueueParamsDto;
use Exception;

class RabbitProduct extends RabbitBase implements ProducerInterface
{

    /**
     * @Author: lpc
     * @DateTime: 2021/5/24 15:51
     * @Description: 立即执行队列
     * @param QueueParamsDto $dto : 队列数据对象
     * @return array
     */
    public function push(QueueParamsDto $dto): array
    {
        //频道
        $channel = $this->channel();
        //创建交换机对象
        $ex = $this->exchange();
        //消息内容
        $jsonData = json_encode(objectToArray($dto));
        //开始事务
        $channel->startTransaction();
        $sendEd = false;
        $config = $this->getConfig();
        foreach ($config['routes'] as $route) {
            $sendEd = $ex->publish($jsonData, $route);
        }

        if (!$sendEd) {
            $channel->rollbackTransaction();
            return self::serviceError('推送失败');
        }
        $channel->commitTransaction(); //提交事务
        $this->close();
        return self::serviceSucc([], '推送成功');
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/24 16:31
     * @Description: 延迟执行队列
     * @param QueueParamsDto $dto : 队列数据对象
     * @param int $delayTime :延时执行时间；单位：秒
     * @return array
     */
    public function delay(QueueParamsDto $dto, int $delayTime): array
    {
        // TODO: Implement delay() method.
    }
}
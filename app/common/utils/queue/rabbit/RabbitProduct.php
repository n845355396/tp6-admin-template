<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/25 13:18
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\queue\rabbit;


use AMQPChannelException;
use AMQPConnection;
use AMQPConnectionException;
use AMQPExchangeException;
use AMQPQueue;
use AMQPQueueException;
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
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws AMQPExchangeException
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

        foreach ($dto->getRoutes() as $route) {
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
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws AMQPExchangeException
     * @throws AMQPQueueException
     */
    public function delay(QueueParamsDto $dto, int $delayTime): array
    {
        //消息内容
        $jsonData = json_encode(objectToArray($dto));
        //频道
        $channel = $this->channel();
        //创建交换机对象
        $dlx = $this->exchange();

        //创建死信交换机以及队列
        $dlxKey = "delayed_run_queue_route";
        $dlxQ   = $this->queue();
        $dlxQ->setName($this->sysTaskConfig['queue_name']);
        $dlxQ->setFlags(AMQP_DURABLE);
        $dlxQ->declareQueue();
        $dlxQ->bind($dlx->getName(), $dlxKey);

        //需要被delay的交换机
        $ex           = $this->exchange();
        $exchangeName = "exchange_delayed";
        $ex->setName($exchangeName);
        $ex->setType(AMQP_EX_TYPE_DIRECT);
        $ex->setFlags(AMQP_DURABLE);
        $ex->declareExchange();

        $config = $this->getConfig();

        //被delayed的队列
        $q = $this->queue();
        $q->setName('delayed_queue_' . $delayTime);
        $q->setFlags(AMQP_DURABLE);
        $arguments = [
            'x-message-ttl'             => $delayTime * 1000, //消息TTL
            'x-dead-letter-exchange'    => $config['exchange'], //死信发送的交换机
            'x-dead-letter-routing-key' => $dlxKey, //死信routeKey
        ];
        //设置属性
        $routeKey = "delayed_route_" . $delayTime;
        $q->setArguments($arguments);
        $q->declareQueue();
        $q->bind($ex->getName(), $routeKey);


        //开始事务
        $channel->startTransaction();
        $sendEd = false;

        $sendEd = $ex->publish($jsonData, $routeKey, AMQP_MANDATORY, array('delivery_mode' => 2));


        if (!$sendEd) {
            $channel->rollbackTransaction();
            return self::serviceError('推送失败');
        }
        $channel->commitTransaction(); //提交事务
        $this->close();
        return self::serviceSucc([], '推送成功');

    }
}
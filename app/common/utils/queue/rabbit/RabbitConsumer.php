<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/25 13:19
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\queue\rabbit;


use app\common\utils\queue\ConsumerInterface;

class RabbitConsumer extends RabbitBase implements ConsumerInterface
{

    /**
     * @Author: lpc
     * @DateTime: 2021/5/24 18:15
     * @Description: 开启消费者
     * @return mixed
     */
    public function run()
    {
        //创建交换机
        $ex = $this->exchange();
        $ex->setType(AMQP_EX_TYPE_DIRECT); //direct类型
        $ex->setFlags(AMQP_DURABLE); //持久化
        //echo "Exchange Status:".$ex->declare()."\n";

        //创建队列
        $q = $this->queue();
        //var_dump($q->declare());exit();
        $q->setName($this->sysTaskConfig['queue_name']);
        $q->setFlags(AMQP_DURABLE); //持久化
        //echo "Message Total:".$q->declareQueue()."\n";

        //绑定交换机与队列，并指定路由键
//        foreach ($this->route as $route) {
//            echo "消费者绑定路由key=>$route: " . $q->bind($this->exchange, $route) . "\n";
//        }

        //todo lpc 目前还不是很明白rabbitMq用法，先这样写上
        $config = $this->getConfig();
        foreach ($config['routes'] as $route) {
            $q->bind($this->exchange, $route);
        }


        //阻塞模式接收消息
        echo "队列消费者已启动:\n";
        while (True) {
            echo "执行中...:\n";
            $q->consume(function ($envelope, $queue) {
                $msg = $envelope->getBody();
                echo $msg . "\n"; //处理消息
                $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答
            });
//            $q->consume('processMessage', AMQP_AUTOACK); //自动ACK应答
        }
        $this->close();
    }
}
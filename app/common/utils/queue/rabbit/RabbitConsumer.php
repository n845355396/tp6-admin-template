<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/25 13:19
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\queue\rabbit;


use app\common\model\TaskMdl;
use app\common\utils\queue\ConsumerInterface;
use Exception;

class RabbitConsumer extends RabbitBase implements ConsumerInterface
{

    /**
     * @Author: lpc
     * @DateTime: 2021/5/24 18:15
     * @Description: 开启消费者
     * @param string $queueName
     * @return mixed
     * @throws Exception
     */
    public function run(string $queueName = '')
    {
        try {
            //创建队列
            $queueObj = $this->queue();
            $queueObj->setName($queueName);
            $queueObj->setFlags(AMQP_DURABLE); //持久化
            $queueObj->declareQueue();

            //lpc 这里批量绑定路由
            $config          = $this->getConfig();
            $defaultExchange = $config['exchange'];
            $routes          = $config['routes'];

            if (!empty($routes[$queueName])) {
                foreach ($routes[$queueName] as $routeKey) {
                    $queueObj->bind($defaultExchange, $routeKey);
                }
            }

            $consumerTag = 'rabbit_mq_default_consumer_tag';

            //阻塞模式接收消息
            echo "队列消费者已启动:\n";
            while (true) {
                echo "执行中...:\n";
                $queueObj->consume(function ($envelope, $queue) {
                    $data      = json_decode($envelope->getBody(), true);
                    $class     = $data['task_class'];
                    $isJobDone = (new $class)->handle($data);

                    if ($isJobDone) {
                        $this->upLog($data['unique_code'], TaskMdl::SUCCESS);
                    } else {
                        $this->upLog($data['unique_code'], TaskMdl::FAILED);
                    }

                    $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答
                }, AMQP_NOPARAM, $consumerTag);
//            $queueObj->consume('processMessage', AMQP_AUTOACK); //自动ACK应答
            }
        } catch (Exception $e) {
            $this->close();
            throw new Exception($e->getMessage());
        }

    }
}
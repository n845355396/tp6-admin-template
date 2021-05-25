<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/24 17:01
 * @Description: thinkphp自带的queue，封装类
 * 
 * @return 

 */

namespace app\common\utils\queue\think_queue;


use app\common\utils\queue\QueueBase;
use app\common\utils\queue\ProducerInterface;
use app\common\utils\queue\QueueParamsDto;
use Exception;
use think\facade\Queue;

class ThinkProducer extends QueueBase implements ProducerInterface
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
        try {
            // 1.当前任务由哪个类来负责处理
            // 当轮到该任务时，系统将生成该类的实例，并调用其fire方法

            //@Author: lpc @Description: 此处不使用$task_name，在下面用到 @DateTime: 2021/5/24 18:18
            $jobHandlerClassName = ThinkConsumer::class;

            // 2.当任务归属的队列名称，如果为新队列，会自动创建
            $jobQueueName = $dto->getQueueName();

            // 3.当前任务所需业务数据，不能为resource类型，其他类型最终将转化为json形式的字符串
            $jobData                               = $dto->getData();
            // 4.将该任务推送到消息列表，等待对应的消费者去执行
            // 入队列，later延迟执行，单位秒，push立即执行
            $isPushed = Queue::push($jobHandlerClassName, objectToArray($dto), $jobQueueName);

            // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
            if ($isPushed !== false) {
                return self::serviceSucc([], '推送成功');
            } else {
                return self::serviceError('推送失败');
            }
        } catch (Exception $e) {
            return self::serviceError($e->getMessage());
        }
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
        try {
            $delayTime = $delayTime < 0 ? 0 : $delayTime;
            // 1.当前任务由哪个类来负责处理
            // 当轮到该任务时，系统将生成该类的实例，并调用其fire方法
            //@Author: lpc @Description: 此处不使用$task_name，在下面用到 @DateTime: 2021/5/24 18:18
            $jobHandlerClassName = ThinkConsumer::class;

            // 2.当任务归属的队列名称，如果为新队列，会自动创建
            $jobQueueName = $dto->getQueueName();

            // 3.当前任务所需业务数据，不能为resource类型，其他类型最终将转化为json形式的字符串
            $jobData                               = $dto->getData();
            $jobData['lpc_think_queue_task_class'] = $dto->getTaskClass();
            // 4.将该任务推送到消息列表，等待对应的消费者去执行
            // 入队列，later延迟执行，单位秒，push立即执行
            $isPushed = Queue::later($delayTime, $jobHandlerClassName, $jobData, $jobQueueName);

            // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
            if ($isPushed !== false) {
                return self::serviceSucc([], '推送成功');
            } else {
                return self::serviceError('推送失败');
            }

        } catch (Exception $e) {
            return self::serviceError($e->getMessage());
        }
    }
}

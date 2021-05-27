<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/24 17:47
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\queue\think_queue;


use app\common\model\TaskMdl;
use app\common\utils\queue\ConsumerInterface;
use app\common\utils\queue\QueueBase;
use think\queue\Job;

//lpc 因为这个是tp自带的队列，我们不实现消费者接口,使用默认的fire方法即可
class ThinkConsumer extends QueueBase implements ConsumerInterface
{
    /**
     * fire方法是消息队列默认调用的方法
     * @param Job $job 当前的任务对象
     * @param array|mixed $data 发布任务时自定义的数据
     */
    public function fire(Job $job, $data)
    {

        $class     = $data['task_class'];
        $isJobDone = (new $class)->handle($data);

        if ($isJobDone) {
            //如果任务执行成功， 记得删除任务
            $job->delete();

            $this->upLog($data['unique_code'], TaskMdl::SUCCESS);
        } else {
            $job->delete();
            $this->upLog($data['unique_code'], TaskMdl::FAILED);
//            if ($job->attempts() > 3) {
//                //通过这个方法可以检查这个任务已经重试了几次了
//                print("<warn>Job已重试超过3次!!" . "</warn>\n");
//                $job->delete();
//                // 也可以重新发布这个任务
//                //print("<info>Hello Job will be availabe again after 2s."."</info>\n");
////                $job->release(2); //$delay为延迟时间，表示该任务延迟2秒后再执行
//            }
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/24 18:15
     * @Description: 开启消费者
     * @param string $queueName
     * @return mixed
     */
    public function run(string $queueName = '')
    {
        // TODO: Implement run() method.
    }
}
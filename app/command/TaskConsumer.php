<?php
declare (strict_types=1);

namespace app\command;


use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Config;
use think\facade\Console;

class TaskConsumer extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('task_consumer')
            ->setDescription('开启系统任务队列消费者');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('task_consumer开始');
        $taskConfig = Config::get("sys_task");
        $type       = $taskConfig['type'];
        $queueName  = $taskConfig['queue_name'];

        //@Author: lpc @Description: think_queue是tp自带队列，单独处理 @DateTime: 2021/5/25 13:00
        if ($type == 'think_queue') {
            $output = Console::call('queue:work', ['--queue', $queueName]);
            $output->fetch();
        } else {
            $config    = $taskConfig['stores'][$type];
            $className = $config['consumer_class_name'];
            (new $className())->run();
        }

        $output->writeln('task_consumer结束');
    }
}

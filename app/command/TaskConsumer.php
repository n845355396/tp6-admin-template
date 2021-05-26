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
            ->setDescription('开启系统任务队列消费者监听');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('系统任务队列消费者监听开始运行...');
        $taskConfig = Config::get("sys_task");
        $type       = $taskConfig['type'];
        $queueNames = implode(',', $taskConfig['all_queue_names']);

        //@Author: lpc @Description: think_queue是tp自带队列，单独处理 @DateTime: 2021/5/25 13:00
        if ($type == 'think_queue') {
            $output = Console::call('queue:work', ['--queue', $queueNames]);
            $output->fetch();
        } else {
            foreach ($taskConfig['all_queue_names'] as $queueName) {
                exec('php think enable_queue ' . $queueName . ' > /dev/null &');
                $output->writeln($queueName);
            }

        }

        $output->writeln('系统任务队列消费者监听结束运行');
    }
}

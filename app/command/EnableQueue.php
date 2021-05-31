<?php
declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Config;

class EnableQueue extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('enable_queue')
            ->addArgument('queue', Argument::OPTIONAL, "需要开启的队列")
            ->setDescription('开启队列任务中定义的消费者');
    }

    protected function execute(Input $input, Output $output)
    {
        $queue = trim($input->getArgument('queue'));

        if (empty($queue)) {
            $output->writeln('一个队列名不存在！');
            return;
        }
        // 指令输出
        $output->writeln('正在运行队列' . $queue);

        $taskConfig = Config::get("sys_task");
        $type       = $taskConfig['type'];
        $config     = $taskConfig['stores'][$type];
        $className  = $config['consumer_class_name'];

        (new $className())->run($queue);

    }
}

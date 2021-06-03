<?php
declare (strict_types=1);

namespace app\command;


use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\App;
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
        $output->writeln('系统任务队列消费者开始启动...');
        $taskConfig = Config::get("sys_task");
        $type       = $taskConfig['type'];
        $queueNames = implode(',', $taskConfig['all_queue_names']);
        $output->writeln('使用的是' . $type . '队列，启动...');

        $queuePath = App::getRuntimePath() . 'queue';
        if (!file_exists($queuePath)) {
            mkdir($queuePath, 0777, true);
        }

        //@Author: lpc @Description: think_queue是tp自带队列，单独处理 @DateTime: 2021/5/25 13:00
        if ($type == 'think_queue') {
            $processName = 'think queue:work "' . $queueNames . '"';
            if ($this->isProcessExist($processName) == 1) {
                $output->writeln("<comment>" . $queueNames . "进程已经存在，无需启动</comment>");
                $output->writeln("<info>" . '查看进程：ps axu|grep "queue:work ' . $queueNames . '"</info>');
                return;
            }

            $logPath = $queuePath . '/think_queue.log';
            $command = "nohup php think queue:work $queueNames >$logPath 2>>&1 &";

            system($command, $result);

            if ($result == 0) {
                $output->writeln("<info>" . $queueNames . "队列启动成功</info>");
                $output->writeln("<info>" . '查看进程：ps axu|grep "queue:work ' . $queueNames . '"</info>');
            } else {
                $output->writeln($queueNames . "<comment>队列启动失败</comment>");
            }

        } else {
            foreach ($taskConfig['all_queue_names'] as $queueName) {
                $processName = "think enable_queue $queueName";
                if ($this->isProcessExist($processName) == 1) {
                    $output->writeln("<comment>" . $queueName . "进程已经存在，无需启动</comment>");
                    $output->writeln("<info>" . '查看进程：ps axu|grep "enable_queue ' . $queueName . '"</info>');
                    continue;
                }

                $logPath = $queuePath . '/' . $queueName . '.log';
                $command = "nohup php think enable_queue $queueName >$logPath 2>>&1 &";
                system($command, $result);

                if ($result == 0) {
                    $output->writeln("<info>" . $queueName . "队列启动成功</info>");
                    $output->writeln("<info>" . '查看进程：ps axu|grep "enable_queue ' . $queueName . '"</info>');
                } else {
                    $output->writeln($queueNames . "<comment>队列启动失败</comment>");
                }
            }
        }
        $output->writeln('系统任务队列消费者启动完成');
    }


    /**
     * @Author: lpc
     * @DateTime: 2021/5/31 9:54
     * @Description: 检查进程是否存在
     * @param $processName : 进程名
     * @return string
     */
    private function isProcessExist($processName): string
    {
        $ps  = 'ps axu|grep "' . $processName . '"|grep -v "grep"|wc -l';
        $ret = shell_exec($ps);
        return rtrim($ret, "\r\n");
    }
}

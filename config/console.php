<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'task_consumer' => 'app\command\TaskConsumer',
        'enable_queue' => 'app\command\EnableQueue',
    ],
];

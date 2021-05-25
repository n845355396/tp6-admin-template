<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 15:54
 * @Description: 第三方支付配置项
 * ${PARAM_DOC}
 * @return ${TYPE_HINT}
${THROWS_DOC}
 */



use app\common\utils\queue\rabbit\RabbitConsumer;
use app\common\utils\queue\rabbit\RabbitProduct;
use app\common\utils\queue\think_queue\ThinkConsumer;
use app\common\utils\queue\think_queue\ThinkProducer;

//lpc 各队列内除了product_class_name、consumer_class_name必填，参数随便定义，对应的配置将会加载进扩展的config属性内
return [
    'type'       => 'rabbit',
    'queue_name' => 'default',//默认队列通道名,使用think_queue请查看queue.php文件下的默认通道
    'stores'     => [
        'think_queue' => [
            'product_class_name'  => ThinkProducer::class,
            'consumer_class_name' => ThinkConsumer::class
        ],
        'rabbit'      => [
            'product_class_name'  => RabbitProduct::class,
            'consumer_class_name' => RabbitConsumer::class,
            //配置
            'host'                => [
                'host'     => '192.168.1.100',
                'port'     => '5673',
                'login'    => 'admin',
                'password' => 'admin123',
                'vhost'    => '/',
            ],
            //交换机
            'exchange'            => 'lpc',
            //路由
            'routes'              => ['lpc_route'],//没搞明白rabbit，这里先这样写，多个route会重复执行
        ]
    ]
];
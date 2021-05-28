<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 15:54
 * @Description: 第三方短信配置项
 * ${PARAM_DOC}
 * @return ${TYPE_HINT}
${THROWS_DOC}
 */


use app\common\utils\sms\zthy\ZthySms;

return [
    'type'         => 'zthy',//使用第三方短信
    'sms_prefix'   => '【我要装修】',
    'sms_template' => [
        //模板使用二选一
        'verification_code' => [
            'name'          => '验证码',
            'platform_code' => '',//短信平台提供短信内容模板code模板
            'local_diy'     => '您的验证码是:{#code}'//本地自定义短信内容模板
        ],
        'test_sms'          => [
            'name'          => '测试短信',
            'platform_code' => '',
            'local_diy'     => '{#name}测试短信发送结果:{#result}'
        ],
    ],
    'stores'       => [
        //助通短信
        'zthy' => [
            'class_name' => ZthySms::class,
            'username'   => '123',
            'password'   => '321',
        ],
    ]
];
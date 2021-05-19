<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 15:54
 * @Description: 第三方支付配置项
 * ${PARAM_DOC}
 * @return ${TYPE_HINT}
${THROWS_DOC}
 */

use app\common\utils\payment\aliAppPay\AliAppPay;

return [
    'ali_app_pay' => [
        'class_name'           => AliAppPay::class,
        //app_id
        'app_id'               => env('ali_app_pay.app_id', ''),
        //开发者私钥去头去尾去回车，一行字符串
        'rsa_private_key'      => env('ali_app_pay.rsa_private_key', ''),
        //支付宝公钥，一行字符串
        'alipay_rsa_public_key' => env('ali_app_pay.alipay_rsa_public_key', ''),
    ]

];
<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 17:07
 * @Description: 文件上传配置
 * ${PARAM_DOC}
 * @return ${TYPE_HINT}
${THROWS_DOC}
 */

use app\common\utils\upload\local\LocalUpload;
use app\common\utils\upload\qiniu\QiniuUpload;

return [
    'type'   => 'local',//存储文件类型
    'stores' => [
        'local' => [
            'class_name' => LocalUpload::class,
            'img_path'   => '/upload/img/',//保存图片根目录
            'file_path'  => '/upload/file/',//保存文件根目录
        ],
        'qiniu' => [
            'class_name' => QiniuUpload::class,
            'qiniu_access_key'    => 'TqmzUSYXlv64puNV5JENIQchWrAEDOs-2IB8_z04',
            'qiniu_access_secret' => 'd22f_7xJLGGoHjMwRe-ItC2aNkH70171B9Zu2X3u',
            'qiniu_bucket'        => 'luzhongsheng',
            'qiniu_domain'        => 'https://img.luzhongsheng.com'
        ]
    ]
];
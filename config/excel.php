<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 15:54
 * @Description: 导入导出配置
 * ${PARAM_DOC}
 * @return ${TYPE_HINT}
${THROWS_DOC}
 */

use app\common\utils\excel\default_excel\DefaultExcel;

return [
    'type'   => 'default',//存储文件类型
    'stores' => [
        'default' => [
            'class_name' => DefaultExcel::class,
            'default_dir'   => 'storage/export',//保存文件根目录
        ],
    ]

];
<?php

use app\admin\exception\AdminExceptionHandle;

// 容器Provider定义文件
return [

    'think\exception\Handle' => AdminExceptionHandle::class
];

<?php

use app\portal\exception\PortalExceptionHandle;

// 容器Provider定义文件
return [

    'think\exception\Handle' => PortalExceptionHandle::class
];

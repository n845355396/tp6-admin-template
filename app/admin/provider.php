<?php

use app\admin\exception\ApiadminEceptionHandle;

// 容器Provider定义文件
return [

    'think\exception\Handle' => ApiadminEceptionHandle::class
];

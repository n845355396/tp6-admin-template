<?php

use app\portal\middleware\AllowCrossDomain;
use app\portal\middleware\RequestParam;
use think\middleware\CheckRequestCache;

return [
    AllowCrossDomain::class,
    RequestParam::class,
];
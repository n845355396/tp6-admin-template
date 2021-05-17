<?php

use app\admin\middleware\AllowCrossDomain;
use app\admin\middleware\RequestParam;

return [
    AllowCrossDomain::class,
    RequestParam::class
];
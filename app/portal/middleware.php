<?php

use app\portal\middleware\AllowCrossDomain;
use app\portal\middleware\RequestParam;

return [
    AllowCrossDomain::class,
    RequestParam::class
];
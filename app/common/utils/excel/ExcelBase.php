<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/31 14:48
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\excel;


use app\common\utils\Result;
use think\facade\Config;

class ExcelBase extends Result
{
    public function __construct()
    {
        $excelConf    = Config::get("excel");
        $type         = $excelConf['type'];
        $this->config = Config::get("excel.stores.{$type}");
    }

    protected $config;
}
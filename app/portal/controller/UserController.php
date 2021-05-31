<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/20 13:29
 * @Description: Description
 * 
 * @return 

 */

namespace app\portal\controller;


use app\common\service\ExcelService;
use app\common\service\Kernel;
use app\common\service\SmsService;
use app\common\service\TaskService;
use app\common\task\TaskBase;
use app\common\task\TestTask;
use app\common\utils\ExcelUtil;
use app\common\utils\queue\QueueParamsDto;
use app\common\utils\queue\think_queue\ThinkProducer;
use app\common\utils\Result;
use app\common\utils\SmsUtil;
use think\facade\Filesystem;
use think\response\Json;

class UserController extends AuthController
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/20 13:29
     * @Description: 获取用户信息
     */
    public function info(): Json
    {
//        "file_path": "storage/export/lpc.csv",
//		"full_file_url": "http://tp6-admin-template.local/storage/export/lpc.csv"
        $fileName = 'lpc';
        $headArr  = ['ID', '年纪', '性别'];
        $data     = [['ID', '12', '男'], ['小明2', '122', '女'], ['小明3', '123', '男']];
        $res      = (Kernel::single(ExcelService::class))->export($fileName,$headArr,$data);
        return Result::disposeServiceRes($res);
    }
}















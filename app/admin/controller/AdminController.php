<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 14:02
 * @Description: 管理员控制器
 *
 * @return

 */

namespace app\admin\controller;


use app\admin\service\AdminService;
use app\common\service\Kernel;
use app\common\utils\upload\local\LocalUpload;
use app\common\utils\Result;
use think\response\Json;

class AdminController extends AuthController
{
    public function list()
    {
       $dd =  Kernel::single(LocalUpload::class)->upload();
        echo "<pre>";
        print_r($dd);
        exit;
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 14:40
     * @Description: 创建平台管理员
     * @return Json
     */
    public function create(): Json
    {
        $data         = $this->request->requestParams;
        $adminService = app()->make(AdminService::class);
        $res          = $adminService->create($data);
        return Result::disposeServiceRes($res);
    }
}








<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 17:27
 * @Description: Description
 * 
 * @return 

 */

namespace app\admin\controller;


use app\common\service\Kernel;
use app\common\service\UploadService;
use app\common\utils\Result;
use think\facade\Route;
use think\response\Json;

class UploadController extends AuthController
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 17:27
     * @Description: 上传图片
     */
    public function image(): Json
    {
        $files = $this->request->file();
        $res   = Kernel::single(UploadService::class)->image($files);
        return Result::disposeServiceRes($res);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 17:27
     * @Description: 上传文件
     */
    public function file(): Json
    {
        $files = $this->request->file();
        $res   = Kernel::single(UploadService::class)->file($files);
        return Result::disposeServiceRes($res);
    }
}
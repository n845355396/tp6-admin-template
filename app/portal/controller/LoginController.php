<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/20 12:52
 * @Description: Description
 * 
 * @return 

 */

namespace app\portal\controller;


use app\common\service\Kernel;
use app\common\utils\Result;
use app\portal\service\UserService;
use think\response\Json;

class LoginController extends BaseController
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/20 12:59
     * @Description: 用户登录
     * @return Json
     */
    public function login(): Json
    {
        $data = $this->request->dataParams;
        $res  = Kernel::single(UserService::class)->login($data);
        return Result::disposeServiceRes($res);
    }
}
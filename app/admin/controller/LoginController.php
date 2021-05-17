<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 13:27
 * @Description: 登录控制器
 * 
 * @return 

 */

namespace app\admin\controller;


use app\admin\service\AdminService;
use app\common\service\Kernel;
use app\common\utils\Captcha;
use app\common\utils\Result;

class LoginController extends BaseController
{

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 14:10
     * @Description: 登录验证码图片
     * @return mixed
     */
    public function getCodeImg()
    {
        return Kernel::single(Captcha::class)->create();
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 13:28
     * @Description: 平台用户登录
     */
    public function login()
    {
        $data = $this->request->requestParams;
        $res  = Kernel::single(AdminService::class)->login($data);
        return Result::disposeServiceRes($res);
    }

}
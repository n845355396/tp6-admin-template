<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/20 13:29
 * @Description: Description
 * 
 * @return 

 */

namespace app\portal\controller;


use app\common\utils\Result;
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
        return Result::succ();
    }
}
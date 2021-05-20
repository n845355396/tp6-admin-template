<?php
/*
 * @Author: lpc
 * @DateTime: 2020/11/16 11:04
 * @Description: 用户验证登录基类
 *
 * @return

 */


namespace app\portal\controller;

//要验证token的全部接口
use app\portal\middleware\CheckToken;

class AuthController extends BaseController
{
    protected $middleware = [CheckToken::class];

}
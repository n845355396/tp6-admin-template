<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 13:39
 * @Description: 接口错误返回
 * 
 * @return 

 */

namespace app\portal\controller;



use app\common\utils\Result;

class ErrorController
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 13:41
     * @Description: 404
     * @return \think\response\Json
     */
    public function noFound(): \think\response\Json
    {
        return Result::error("url请求错误！", Result::NO_FOUND);
    }

    public function index()
    {
        return Result::error("这不是你该看的！");
    }
}
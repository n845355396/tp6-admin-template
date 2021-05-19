<?php


namespace app\admin\middleware;


use Closure;

class RequestParam
{


    public function handle($request, Closure $next)
    {
        //@Author: lpc @Description: 将数据在此处统一获取，为业务出现变化获取方式发生改变时可方便操作 @DateTime: 2021/5/17 13:36
        $params                 = $request->param();
        $request->dataParams = $params;

        return $next($request);
    }


}
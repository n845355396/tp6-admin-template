<?php


namespace app\admin\middleware;

use app\common\service\JwtService;
use app\common\utils\Result;

class CheckToken
{


    public function handle($request, \Closure $next)
    {
        $token = $request->header('accessToken');
        if (empty($token)) {
            return Result::error("非法操作", Result::TOKEN_ERROR);
        }
        $result = JwtService::checkToken($token);
        if (!$result['status']) {
            return Result::error($result['msg']);
        }

        $request->adminInfo = $result['data']['data'];
        $request->adminId  = $result['data']['data']->adminId;

        return $next($request);
    }


}
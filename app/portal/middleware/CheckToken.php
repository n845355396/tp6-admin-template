<?php


namespace app\portal\middleware;

use app\admin\service\AdminService;
use app\admin\service\MenuService;
use app\admin\service\PermissionService;
use app\common\service\JwtService;
use app\common\service\Kernel;
use app\common\utils\Result;
use app\portal\service\UserService;
use think\facade\Route;
use think\route\RuleItem;

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

        $request->userInfo = $result['data']['data'];
        $request->userId   = $result['data']['data']->user_id;
        $password          = $result['data']['data']->password;

        //@Author: lpc @Description: 前台的用户不验证密码是否一致了，因为要查询数据库，我怕在一些情况下数据库会出问题 @DateTime: 2021/6/25 18:38
        //lpc 这里我们来判断下用户密码是否被修改，修改后就算token正确都要重新登录
//        $isAs = Kernel::single(UserService::class)->checkPass($request->userId, $password);
//        if (!$isAs) {
//            return Result::error('请重新登录！', Result::TOKEN_ERROR);
//        }

        return $next($request);
    }


}
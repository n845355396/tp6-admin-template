<?php


namespace app\admin\middleware;

use app\admin\service\AdminService;
use app\admin\service\MenuService;
use app\admin\service\PermissionService;
use app\common\service\JwtService;
use app\common\service\Kernel;
use app\common\utils\Result;
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

        $request->adminInfo = $result['data']['data'];
        $request->adminId   = $result['data']['data']->admin_id;
        $password           = $result['data']['data']->password;

        //lpc 这里我们来判断下用户密码是否被修改，修改后就算token正确都要重新登录
        $isAs = Kernel::single(AdminService::class)->checkPass($request->adminId, $password);
        if (!$isAs) {
            return Result::error('请重新登录！', Result::TOKEN_ERROR);
        }

        //lpc 拿到了登录id，开始做功能权限判断
        $curRouteObjArr = Route::getRule(request()->rule()->getRule());
        $curRouteObj    = reset($curRouteObjArr);
        $appendData     = $curRouteObj->getOption('append');

        if ($appendData != null && !empty($appendData['is_permission']) && $appendData['is_permission']) {
            $has = Kernel::single(PermissionService::class)
                ->adminHasPermission($request->adminId, request()->rule()->getRoute());
            if (!$has) {
                return Result::error("暂无此操作权限！", Result::NO_PERMISSION);
            }
        }
        return $next($request);
    }


}
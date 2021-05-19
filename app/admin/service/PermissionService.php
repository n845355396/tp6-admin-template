<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/18 14:19
 * @Description: Description
 * 
 * @return 

 */

namespace app\admin\service;


use app\admin\model\AdminMdl;
use app\admin\model\RolePermissionMdl;
use app\common\service\Kernel;
use app\common\utils\Result;
use think\facade\Route;
use think\Model;

class PermissionService extends BaseService
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 14:20
     * @Description: 查询管理员是否有此权限
     * @param $adminId
     * @param $route
     */
    public function adminHasPermission($adminId, $route)
    {
        $adminServie = Kernel::single(AdminService::class);
        if ($adminServie->getAdminType($adminId)) {
            //超级管理员无视权限
            return true;
        }
        $permissionMdl = new RolePermissionMdl();
        $resData       = $adminServie->getAdminRoleInfo($adminId);
        if (!$resData['status']) {
            return Result::serviceError($resData['msg']);
        }
        $roleId = $resData['data']['role_id'];

        $has = $permissionMdl->where(['role_id' => $roleId, 'route' => $route])->count();

        if ($has > 0) {
            return true;
        }
        return false;
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 13:46
     * @Description: 获取平台路由权限列表
     */
    public function routeList(): array
    {
        return Route::getRuleList();
    }

}
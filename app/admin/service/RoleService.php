<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 12:50
 * @Description: Description
 * 
 * @return 

 */

namespace app\admin\service;


use app\admin\model\AdminRoleMdl;
use app\admin\model\RoleMdl;
use app\admin\validate\RoleValidate;
use app\common\utils\Result;
use think\exception\ValidateException;
use think\facade\Route;

class RoleService extends BaseService
{
    /**
     * @var RoleMdl
     */
    private $roleMdl;

    public function __construct()
    {
        parent::__construct();
        $this->roleMdl = new RoleMdl();
    }

    public function disable($roleId, $status = 0): array
    {
        $has = $this->roleMdl->where(['role_id' => $roleId, 'is_super_role' => 1])->count();
        if ($has) {
            return Result::serviceError('超级管理员角色不需要操作！');
        }
        $arMdl = new AdminRoleMdl();
        $has   = $arMdl->where(['role_id' => $roleId])->count();
        if ($has > 0) {
            return Result::serviceError("不能操作正在使用的角色");
        }
        $status = empty($status) ? 0 : 1;
        return $this->roleMdl->disableRole($roleId, $status);
    }


    public function delete($roleId): array
    {
        $arMdl = new AdminRoleMdl();
        $has   = $arMdl->where(['role_id' => $roleId])->count();
        if ($has > 0) {
            return Result::serviceError("不能删除正在使用的角色");
        }
        $has = $this->roleMdl->where(['is_super_role' => 1, 'role_id' => $roleId])->count();
        if ($has > 0) {
            return Result::serviceError("不能删除超级管理员角色");
        }
        return $this->roleMdl->roleDelete($roleId);
    }

    public function list($where, $pageData): array
    {
        return $this->roleMdl->list($where, $pageData);
    }

    public function info($roleId): array
    {
        return $this->roleMdl->roleInfo($roleId);
    }

    public function edit($adminId, $data): array
    {
        try {
            validate(RoleValidate::class)->scene('edit')->check($data);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return Result::serviceError($e->getMessage());
        }
        return $this->roleMdl->saveRole($adminId, $data);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 13:20
     * @Description: 创建用户
     * @param $adminId
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function create($adminId, $data): array
    {
        try {
            validate(RoleValidate::class)->scene('create')->check($data);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return Result::serviceError($e->getMessage());
        }
        return $this->roleMdl->saveRole($adminId, $data);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 12:50
     * @Description: 获取权限列表
     */
    public function permissionList(): array
    {
        $resList   = [];
        $routeList = Route::getRuleList();
        foreach ($routeList as $route) {
            $isPermission = @$route['option']['append']['is_permission'];
            if ($isPermission != null && $isPermission === true) {

                $childData = [
                    'name' => $route['name'],
                    'rule' => $route['rule']
                ];

                $ruleArr   = explode('/', $route['rule']);
                $groupName = reset($ruleArr);
                if (!array_key_exists($groupName, $resList)) {
                    $group = Route::getGroup($groupName);
                    if ($group == null) {
                        $groupName                   = 'other';
                        $resList[$groupName]['name'] = '其他';
                    } else {
                        $resList[$groupName]['name'] = $group->getName();
                    }
                }
                $resList[$groupName]['child_list'][] = $childData;

            }

        }

        return $resList;
    }
}
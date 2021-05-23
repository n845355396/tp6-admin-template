<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/18 13:24
 * @Description: 角色表
 * 
 * @return 

 */

namespace app\admin\model;


use app\common\utils\Result;
use Exception;
use think\facade\Db;
use think\facade\Route;

/**
 * @method leftJoin(string $string, string $string1)
 */
class RoleMdl extends BaseModel
{
    protected $table = 'sys_role';

    public function relMenu()
    {
        return $this->hasMany(RoleMenuMdl::class, 'role_id', 'role_id');
    }

    public function relChildMenu()
    {
        return $this->hasMany(MenuMdl::class, 'menu_id', 'menu_id');
    }

    public function relPermission()
    {
        return $this->hasMany(RolePermissionMdl::class, 'role_id', 'role_id');
    }


    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 13:38
     * @Description: 保存角色主表
     * @param $adminId
     * @param $data
     * @throws Exception
     */
    private function saveRoleInfo($adminId, &$data): void
    {
        try {
            $nowTime = time();
            if (empty($data['role_id'])) {
                $data['create_by']   = $adminId;
                $data['create_time'] = $nowTime;
                $data['update_time'] = $nowTime;
                $roleId              = $this->strict(false)->insertGetId($data);
                $data['role_id']     = $roleId;
            } else {
                $data['update_by']   = $adminId;
                $data['update_time'] = $nowTime;
                $this->where(['role_id' => $data['role_id']])->strict(false)->save($data);
            }
            return;
        } catch (Exception $e) {
            throw  new Exception($e->getMessage());
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 13:46
     * @Description: 保存角色菜单
     * @param $roleId
     * @param $menuIds
     * @return void
     * @throws Exception
     */
    private function saveRelMenuInfo($roleId, $menuIds): void
    {
        try {
            $roleMenuMdl = new RoleMenuMdl();
            $roleMenuMdl->where(['role_id' => $roleId])->delete();
            if (empty($menuIds)) {
                return;
            }
            $menuIdArr = $menuIds;
            if (!is_array($menuIds)) {
                $menuIdArr = explode(',', $menuIds);
            }

            //lpc 此处防止只加入了菜单子类，没加子类的父类导致可能显示不出问题
            $childList = (new MenuMdl())->field('parent_id')
                ->where([['menu_id', 'in', $menuIdArr], ['parent_id', '<>', 0]])->select()->toArray();
            if ($childList) {
                $parentIds = array_column($childList, 'parent_id');
                $menuIdArr = array_merge($menuIdArr, $parentIds);
            }

            $menuIdArr = array_unique($menuIdArr);
            $saveList  = [];
            $nowTime   = time();
            foreach ($menuIdArr as $menuId) {
                if (empty($menuId)) {
                    continue;
                }
                $saveList[] = [
                    'role_id'     => $roleId,
                    'menu_id'     => $menuId,
                    'create_time' => $nowTime
                ];
            }
            $roleMenuMdl->insertAll($saveList);
            return;
        } catch (Exception $e) {
            throw  new Exception($e->getMessage());
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 14:07
     * @Description: 保存角色权限表
     * @param $roleId
     * @param $permissionRules
     * @return void
     * @throws Exception
     */
    private function saveRelPermissionInfo($roleId, $permissionRules): void
    {
        try {
            $rpMdl = new RolePermissionMdl();
            $rpMdl->where(['role_id' => $roleId])->delete();
            if (empty($permissionRules)) {
                return;
            }
            $permissionRuleArr = $permissionRules;
            if (!is_array($permissionRules)) {
                $permissionRuleArr = explode(',', $permissionRules);
            }
            $permissionRuleArr = array_unique($permissionRuleArr);
            $saveList          = [];
            $nowTime           = time();

            foreach ($permissionRuleArr as $rule) {
                if (empty($rule)) {
                    continue;
                }
                $ruleObjArr = Route::getRule($rule);
                $routObj    = reset($ruleObjArr);
                if (!$routObj) {
                    continue;
                }
                $saveList[] = [
                    'role_id'         => $roleId,
                    'permission_name' => $routObj->getName(),
                    'route'           => $routObj->getRoute(),
                    'rule'            => $routObj->getRule(),
                    'create_time'     => $nowTime
                ];
            }
            $rpMdl->insertAll($saveList);
            return;
        } catch (Exception $e) {
            throw  new Exception($e->getMessage());
        }
    }

    public function saveRole($adminId, $data): array
    {
        // 启动事务
        Db::startTrans();
        try {
            //@Author: lpc @Description: 保存主表 @DateTime: 2021/5/19 13:33
            $this->saveRoleInfo($adminId, $data);
            $roleId = $data['role_id'];
            //@Author: lpc @Description: 保存关联菜单表 @DateTime: 2021/5/19 13:33
            $this->saveRelMenuInfo($roleId, $data['menu_ids']);
            //@Author: lpc @Description: 保存关联权限表 @DateTime: 2021/5/19 13:33
            $this->saveRelPermissionInfo($roleId, $data['permission_rules']);

            // 提交事务
            Db::commit();
            return Result::serviceSucc();
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function roleInfo($roleId): array
    {
        $field = '*';
        $info  = $this->field($field)->where(['role_id' => $roleId])
            ->with([
                'rel_menu'       => function ($query) {
                    $query->field('id,menu_id,role_id');
                },
                'rel_permission' => function ($query) {
                    $query->field('id,permission_name,rule,role_id');
                }])
            ->find();
        if (is_null($info)) {
            return [];
        }
        $info = $info->toArray();
        //lpc 筛选出一个只有子集菜单的字段数组粗来
        $relMenu                = $info['rel_menu'];
        $info['rel_child_menu'] = [];
        if ($relMenu) {
            $menuIds  = array_column($relMenu, 'menu_id');
            $menuList = (new MenuMdl())->field('menu_id')
                ->where([['menu_id', 'in', $menuIds], ['parent_id', '<>', 0]])->select()->toArray();
            $menuList = array_column($menuList, 'menu_id');

            foreach (array_column($relMenu, null, 'menu_id') as $key => $value) {
                if (in_array($key, $menuList)) {
                    $info['rel_child_menu'][] = $value;
                }
            }
        }

        return $info;
    }

    public function list($where, $pageData)
    {
        $field = '*';
        return $this->field($field)->where($where)->order('sort asc,update_time desc')
            ->paginate($pageData)->toArray();

    }

    public function roleDelete($roleId): array
    {
        $res = $this->where(['role_id' => $roleId])->delete();
        (new RoleMenuMdl())->where(['role_id' => $roleId])->delete();
        (new RolePermissionMdl())->where(['role_id' => $roleId])->delete();
        return $res ? Result::serviceSucc() : Result::serviceError();
    }

    public function disableRole($roleId, $status): array
    {
        $res = $this->where(['role_id' => $roleId])->update(['status' => $status]);
        return $res ? Result::serviceSucc() : Result::serviceError();
    }


}
















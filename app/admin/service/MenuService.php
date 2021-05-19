<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/18 13:40
 * @Description: 菜单服务类
 * 
 * @return 

 */

namespace app\admin\service;


use app\admin\model\MenuMdl;
use app\admin\model\RoleMenuMdl;
use app\common\service\Kernel;
use app\common\utils\Result;
use think\facade\Route;

class MenuService extends BaseService
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 13:42
     * @Description: 获取管理员菜单列表
     * @param $adminId
     */
    public function list($adminId): array
    {
        $isSuper = Kernel::single(AdminService::class)->getAdminType($adminId);
        $where[] = ['hidden', '=', 0];
        if (!$isSuper) {
            $roleData = Kernel::single(AdminService::class)->getAdminRoleInfo($adminId);
            if (!$roleData['status']) {
                return Result::serviceError($roleData['msg']);
            }
            $roleId       = $roleData['data']['role_id'];
            $roleMenuData = (new RoleMenuMdl())
                ->field('menu_id')->where(['role_id' => $roleId])->select()->toArray();

            if ($roleMenuData) {
                $menuIds = array_column($roleMenuData, 'menu_id');
                $where[] = ['menu_id', 'in', $menuIds];
            }
        }
        $list = (new MenuMdl())->where($where)->order('sort asc,create_time desc')->select()->toArray();
        $list = $this->recursionMenu($list, 0);

        return Result::serviceSucc($list);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 16:51
     * @Description: 递归处理菜单列表关系
     * @param $list
     */
    public function recursionMenu($list, $id): array
    {
        $resList = [];
        foreach ($list as $key => $value) {
            if ($value['parent_id'] == $id) {
                $childrenItems       = $this->recursionMenu($list, $value['menu_id']);
                $value['child_list'] = $childrenItems;
                $resList[]           = $value;
            }
        }
        return $resList;
    }


}




















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
use app\admin\validate\MenuValidate;
use app\common\service\Kernel;
use app\common\utils\Result;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\exception\ValidateException;
use think\facade\Route;

class MenuService extends BaseService
{

    /**
     * @var MenuMdl
     */
    private $menuMdl;

    public function __construct()
    {
        parent::__construct();
        $this->menuMdl = new MenuMdl();
    }

    public function edit($data): array
    {
        try {
            validate(MenuValidate::class)->scene('edit')->check($data);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return Result::serviceError($e->getMessage());
        }
        return $this->menuMdl->saveMenu($data);
    }

    public function create($data): array
    {
        try {
            validate(MenuValidate::class)->scene('create')->check($data);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return Result::serviceError($e->getMessage());
        }
        return $this->menuMdl->saveMenu($data);
    }

    public function hidden($menuId, $hidden): array
    {
        $hidden = $hidden == 0 ? 0 : 1;
        $res    = $this->menuMdl->where(['menu_id' => $menuId])->update(['hidden' => $hidden]);
        return $res ? Result::serviceSucc() : Result::serviceError();
    }

    public function delete($menuId): array
    {
        //删除前判断菜单是否正在使用,是否含有子集
        $has = $this->menuMdl->where(['parent_id'=>$menuId])->count();
        if ($has > 0) {
            return Result::serviceError('菜单存在子级, 无法删除！');
        }
        $has = (new RoleMenuMdl())->where(['menu_id' => $menuId])->count();
        if ($has > 0) {
            return Result::serviceError('菜单有被角色使用,无法删除！');
        }
        $res = $this->menuMdl->where(['menu_id' => $menuId])->delete();
        return $res ? Result::serviceSucc() : Result::serviceError();
    }

    public function info($menuId): array
    {
        $info = $this->menuMdl->where(['menu_id' => $menuId])->find();
        if (is_null($info)) {
            return [];
        }
        return $info->toArray();
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 13:42
     * @Description: 获取管理员菜单列表
     * @param $where
     * @param $adminId
     * @param $notNeedKey : 是否需要返回列表的key为rule
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function list($where, $adminId, $notNeedKey): array
    {
        $isSuper = Kernel::single(AdminService::class)->getAdminType($adminId);

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

        $list = $this->menuMdl->where($where)->order('sort asc,create_time desc')->select()->toArray();
        $list = $this->recursionMenu($list, 0, $notNeedKey);

        return Result::serviceSucc($list);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 16:51
     * @Description: 递归处理菜单列表关系
     * @param $list
     * @param $id
     * @param $notNeedKey
     * @return array
     */
    public function recursionMenu($list, $id, $notNeedKey): array
    {
        $resList = [];
        foreach ($list as $key => $value) {
            if ($value['parent_id'] == $id) {
                $childrenItems       = $this->recursionMenu($list, $value['menu_id'], $notNeedKey);
                $value['child_list'] = $childrenItems;
                if ($notNeedKey) {
                    $resList[] = $value;
                } else {
                    $resList[$value['mark_name']] = $value;
                }
            }
        }
        return $resList;
    }


}




















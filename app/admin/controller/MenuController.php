<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/18 13:29
 * @Description: 菜单控制器
 * 
 * @return 

 */

namespace app\admin\controller;


use app\admin\service\MenuService;
use app\common\service\Kernel;
use app\common\utils\PayUtil;
use app\common\utils\Result;
use think\response\Json;

class MenuController extends AuthController
{
    public function delete(): Json
    {
        $data = $this->dataParams;
        if (empty($data['menu_id'])) {
            return Result::error('菜单id不存在！');
        }
        $res = Kernel::single(MenuService::class)
            ->delete($data['menu_id']);
        return Result::disposeServiceRes($res);
    }

    public function hidden(): Json
    {
        $data = $this->dataParams;
        if (empty($data['menu_id'])) {
            return Result::error('菜单id不存在！');
        }
        if (empty($data['hidden'])) {
            $data['hidden'] = 0;
        }
        $res = Kernel::single(MenuService::class)
            ->hidden($data['menu_id'], $data['hidden']);
        return Result::disposeServiceRes($res);
    }


    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 13:29
     * @Description: 获取菜单列表
     */
    public function list(): Json
    {
        $data       = $this->dataParams;
        $notNeedKey = !empty($data['not_needed_key']);

        $where = [];
        if (empty($data['is_show_hidden'])) {
            $where[] = ['hidden', '=', 0];
        }

        $adminId = $this->getAdminId();

        $res = Kernel::single(MenuService::class)
            ->list($where, $adminId, $notNeedKey);
        return Result::disposeServiceRes($res);
    }

    public function info(): Json
    {
        $data = $this->dataParams;
        if (empty($data['menu_id'])) {
            return Result::error('菜单id不存在！');
        }
        $info = Kernel::single(MenuService::class)
            ->info($data['menu_id']);
        return Result::succ($info);
    }

    public function edit(): Json
    {
        $data        = $this->dataParams;
        $res         = Kernel::single(MenuService::class)
            ->edit($data);
        $res['data'] = [];
        return Result::disposeServiceRes($res);
    }

    public function create(): Json
    {
        $data        = $this->dataParams;
        $res         = Kernel::single(MenuService::class)
            ->create($data);
        $res['data'] = [];
        return Result::disposeServiceRes($res);
    }

}


















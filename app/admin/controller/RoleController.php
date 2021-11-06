<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 12:27
 * @Description: 角色控制器
 * 
 * @return 

 */

namespace app\admin\controller;


use app\admin\service\RoleService;
use app\common\service\Kernel;
use app\common\utils\Result;
use think\facade\Route;
use think\response\Json;

class RoleController extends AuthController
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 12:47
     * @Description: 获取权限组列表
     */
    public function permissionList(): Json
    {
        $list = Kernel::single(RoleService::class)->permissionList();
        return Result::succ($list);
    }

    public function disable(): Json
    {
        $data = $this->request->dataParams;
        if (empty($data['role_id'])) {
            return Result::error('角色id不存在！');
        }

        if (empty($data['status'])) {
            $data['status'] = 0;
        }

        $roleService = Kernel::single(RoleService::class);
        $res         = $roleService->disable($data['role_id'], $data['status']);
        return Result::disposeServiceRes($res);
    }


    public function delete(): Json
    {
        $data = $this->request->dataParams;
        if (empty($data['role_id'])) {
            return Result::error('角色id不存在！');
        }  
        $info = Kernel::single(RoleService::class)->delete($data['role_id']);
        return Result::disposeServiceRes($info);
    }

    public function list(): Json
    {
        $data     = $this->request->dataParams;
        $pageData = $this->getPageData(@$data['page_no'], @$data['page_size']);
        $where    = [];
        if (!empty($data['query_word'])) {
            $where[] = ['role_name', 'like', "%{$data['query_word']}%"];
        }
        if (isset($data['status']) && is_numeric($data['status'])) {
            $status  = $data['status'] == 1 ? 1 : 0;
            $where[] = ['status', '=', $status];
        }
        if (!empty($data['hide_super'])) {
            $where[] = ['is_super_role', '=', 0];
        }
        $list = Kernel::single(RoleService::class)->list($where, $pageData);
        return Result::succ($list);
    }

    public function info(): Json
    {
        $data = $this->request->dataParams;
        if (empty($data['role_id'])) {
            return Result::error('角色id不存在！');
        }
        $info = Kernel::single(RoleService::class)->info($data['role_id']);
        return Result::succ($info);
    }

    public function edit(): Json
    {
        $data    = $this->request->dataParams;
        $adminId = $this->request->adminId;
        $res     = Kernel::single(RoleService::class)->edit($adminId, $data);
        return Result::disposeServiceRes($res);
    }

    public function create(): Json
    {
        $data    = $this->request->dataParams;
        $adminId = $this->request->adminId;
        $res     = Kernel::single(RoleService::class)->create($adminId, $data);
        return Result::disposeServiceRes($res);
    }
}

























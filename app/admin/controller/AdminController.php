<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 14:02
 * @Description: 管理员控制器
 *
 * @return

 */

namespace app\admin\controller;


use app\admin\service\AdminService;
use app\common\service\Kernel;
use app\common\utils\upload\local\LocalUpload;
use app\common\utils\Result;
use think\response\Json;

class AdminController extends AuthController
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 17:14
     * @Description: 管理员列表
     */
    public function list(): Json
    {
        $data     = $this->request->dataParams;
        $pageData = $this->getPageData(@$data['page_no'], @$data['page_size']);
        $where    = [];
        if (!empty($data['query_word'])) {
            $where[] = ['login_name', 'like', '%' . $data['query_word'] . '%'];
        }
        $res = Kernel::single(AdminService::class)->list($where, $pageData);
        return Result::disposeServiceRes($res);
    }

    public function upPassword(): Json
    {
        $data = $this->request->dataParams;

        $adminService = Kernel::single(AdminService::class);
        $res          = $adminService->upPassword($data);
        return Result::disposeServiceRes($res);
    }

    public function disable(): Json
    {
        $data = $this->request->dataParams;

        if (empty($data['admin_id'])) {
            return Result::error('管理员id不存在！');
        }
        if (empty($data['status'])) {
            $data['status'] = 0;
        }
        $adminService = Kernel::single(AdminService::class);
        $res          = $adminService->disable($data['admin_id'], $data['status']);
        return Result::disposeServiceRes($res);
    }

    public function delete(): Json
    {
        $data = $this->request->dataParams;

        if (empty($data['admin_id'])) {
            return Result::error('管理员id不存在！');
        }

        $adminService = Kernel::single(AdminService::class);
        $res          = $adminService->delete($data['admin_id']);
        return Result::disposeServiceRes($res);
    }

    public function info(): Json
    {
        $data = $this->request->dataParams;

        if (empty($data['admin_id'])) {
            return Result::error('管理员id不存在！');
        }

        $adminService = Kernel::single(AdminService::class);
        $res          = $adminService->info($data['admin_id']);
        return Result::succ($res);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 10:45
     * @Description: 管理员编辑
     * @return Json
     */
    public function edit(): Json
    {
        $data         = $this->request->dataParams;
        $adminService = Kernel::single(AdminService::class);
        $res          = $adminService->edit($data);
        return Result::disposeServiceRes($res);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 14:40
     * @Description: 创建平台管理员
     * @return Json
     */
    public function create(): Json
    {
        $data         = $this->request->dataParams;
        $adminService = Kernel::single(AdminService::class);
        $res          = $adminService->create($data);
        return Result::disposeServiceRes($res);
    }
}








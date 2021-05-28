<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/27 14:50
 * @Description: Description
 * 
 * @return 

 */

namespace app\admin\controller;


use app\admin\service\RoleService;
use app\common\service\Kernel;
use app\common\service\TaskService;
use app\common\utils\Result;
use think\response\Json;

class TaskController extends AuthController
{
    public function list(): Json
    {
        $data     = $this->dataParams;
        $pageData = $this->getPageData(@$data['page_no'], @$data['page_size']);
        $where    = [];
        if (!empty($data['status'])) {
            $where[] = ['result', '=', $data['status']];
        }

        $list = Kernel::single(TaskService::class)->list($where, $pageData);
        return Result::succ($list);
    }
}
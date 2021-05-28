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
use app\common\service\SmsService;
use app\common\service\TaskService;
use app\common\utils\Result;
use think\response\Json;

class SmsController extends AuthController
{
    public function retry(): Json
    {
        $data = $this->dataParams;
        if (empty($data['sms_id'])) {
            return Result::error('短信id必填！');
        }
        $res = Kernel::single(SmsService::class)->smsRetry($data['sms_id']);
        return Result::disposeServiceRes($res);
    }

    public function list(): Json
    {
        $data     = $this->dataParams;
        $pageData = $this->getPageData(@$data['page_no'], @$data['page_size']);
        $where    = [];
        if (!empty($data['status'])) {
            $where[] = ['status', '=', $data['status']];
        }

        $list = Kernel::single(SmsService::class)->list($where, $pageData);
        return Result::succ($list);
    }
}
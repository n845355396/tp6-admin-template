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
use app\common\utils\Result;
use think\response\Json;

class MenuController extends AuthController
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 13:29
     * @Description: 获取菜单列表
     */
    public function list(): Json
    {
        $adminId = $this->request->adminId;
        $res     = Kernel::single(MenuService::class)
            ->list($adminId);
        return Result::disposeServiceRes($res);
    }
}


















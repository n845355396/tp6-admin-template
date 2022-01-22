<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/18 13:41
 * @Description: Description
 * 
 * @return 

 */

namespace app\admin\service;

use app\common\service\BaseService as CommonBaseService;

class BaseService extends CommonBaseService
{
    /**
     * @var object|\think\App
     */
    private $request;

    public function __construct()
    {
        $this->request = app('request');
        parent::__construct();
    }

    /**
     * 获取管理员id
     * @return mixed|object
     */
    public function getAdminId()
    {
        return $this->request->adminId;
    }

    /**
     * 获取管理员信息
     * @return mixed|object
     */
    public function getAdminInfo()
    {
        return $this->request->adminInfo;
    }
}
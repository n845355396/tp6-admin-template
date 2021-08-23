<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/18 13:41
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\service;


use think\facade\Db;

class BaseService
{
    public function __construct()
    {
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/7/31 10:50
     * @Description: 开启事务
     */
    public function startTrans()
    {
        Db::startTrans();
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/7/31 10:50
     * @Description: 提交事务
     */
    public function commit()
    {
        Db::commit();
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/7/31 10:51
     * @Description: 回滚事务
     */
    public function rollback()
    {
        Db::rollback();
    }
}
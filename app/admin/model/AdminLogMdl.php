<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 14:55
 * @Description: Description
 * 
 * @return 

 */

namespace app\admin\model;


use app\admin\logEntity\AdminUserEntity;
use app\common\utils\Result;
use app\common\utils\UserPassword;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\model\relation\HasOneThrough;

class AdminLogMdl extends BaseModel
{
    protected $table = 'sys_admin_log';
    protected $pk = 'log_id';

}
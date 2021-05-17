<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 14:55
 * @Description: Description
 * 
 * @return 

 */

namespace app\admin\model;


use app\common\utils\Result;
use app\common\utils\UserPassword;
use Exception;
use think\Model;

class AdminMdl extends BaseModel
{
    protected $table = 'sys_admin';

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 15:40
     * @Description: 保存管理员用户
     * @param $data
     * @return array
     * @throws Exception
     */
    public function saveAdminUser(&$data)
    {
        try {
            //lpc 数据填充
            $data['nickname'] = empty($data['nickname']) ? $data['login_name'] : $data['nickname'];
            if (!empty($data['password'])) {
                $data['password'] = UserPassword::encrypt($data['password']);
            }
            if (empty($data['admin_id'])) {
                $data['update_time'] = $data['create_time'] = time();
                $id                  = $this->insertGetId($data);
                $data['admin_id']    = $id;
            } else {
                $data['update_time'] = time();
                $this->save($data);
            }
            return Result::serviceSucc();

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
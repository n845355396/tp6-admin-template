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
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\Model;
use think\model\relation\HasOneThrough;

class AdminMdl extends BaseModel
{
    protected $table = 'sys_admin';
    protected $pk = 'admin_id';

    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 17:52
     * @Description: 管理员角色信息
     * @return HasOneThrough
     */
    public function role(): HasOneThrough
    {
        return $this
            ->hasOneThrough(RoleMdl::class, AdminRoleMdl::class, 'role_id', 'role_id', 'admin_id', 'role_id');
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 12:06
     * @Description: 修改管理员密码
     * @param $adminId
     * @param $password
     * @return array
     */
    public function upPassword($adminId, $password): array
    {
        $hashPassword = UserPassword::encrypt($password);;
        $res = $this->where(['admin_id' => $adminId])->update(['password' => $hashPassword]);
        return $res ? Result::serviceSucc() : Result::serviceError();
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 12:04
     * @Description: 禁用/启用管理员
     * @param $adminId
     * @param $status
     * @return array
     */
    public function disableAdmin($adminId, $status): array
    {
        $res = $this->where(['admin_id' => $adminId])->update(['status' => $status]);
        return $res ? Result::serviceSucc() : Result::serviceError();
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 12:00
     * @Description: 删除管理员
     * @param $adminId
     * @return array
     */
    public function delAdmin($adminId): array
    {
        $res = $this->where(['admin_id' => $adminId])->delete();
        return $res ? Result::serviceSucc() : Result::serviceError('删除失败！');
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 12:01
     * @Description: 获取管理员信息
     * @param $adminId
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function infoAdmin($adminId): array
    {
        $field = 'admin_id,is_super,status,avatar,login_name,nickname,mobile,update_time,create_time';
        $info  = $this->field($field)->with(['role'])->find($adminId);
        if (is_null($info)) {
            return [];
        }
        return $info->toArray();
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 15:40
     * @Description: 保存管理员用户
     * @param $data
     * @return array
     * @throws Exception
     */
    public function saveAdminUser(&$data): array
    {
        // 启动事务
        Db::startTrans();
        try {
            //lpc 数据填充
            $data['nickname'] = empty($data['nickname']) ? $data['login_name'] : $data['nickname'];
            if (!empty($data['password'])) {
                $data['password'] = UserPassword::encrypt($data['password']);
            }
            if (empty($data['admin_id'])) {
                $data['update_time'] = $data['create_time'] = time();
                $id                  = $this->strict(false)->insertGetId($data);
                $data['admin_id']    = $id;
            } else {
                $data['update_time'] = time();
                $this->update($data);
            }

            //给管理员保存角色
            $adminRoleMdl = new AdminRoleMdl();
            $adminRoleMdl->where(['admin_id' => $data['admin_id']])->delete();
            $adminRoleMdl->insert(['admin_id' => $data['admin_id'], 'role_id' => $data['role_id']]);

            // 提交事务
            Db::commit();
            return Result::serviceSucc();

        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new Exception($e->getMessage());
        }
    }
}
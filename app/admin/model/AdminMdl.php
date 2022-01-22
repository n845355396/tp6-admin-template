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
use app\admin\logEntity\BaseLogEntity;
use app\common\service\ModelFiledLogService;
use app\common\utils\Result;
use app\common\utils\UserPassword;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\model\relation\HasOneThrough;

class AdminMdl extends BaseModel implements ModelLogInterface
{
    protected $table = 'sys_admin';
    protected $pk = 'admin_id';

//    /**
//     * @Author: lpc
//     * @DateTime: 2021/5/18 17:52
//     * @Description: 管理员角色信息
//     * @return HasOneThrough
//     */
//    public function role(): HasOneThrough
//    {
//        return $this
//            ->hasOneThrough(RoleMdl::class, AdminRoleMdl::class, 'role_id', 'role_id', 'admin_id', 'role_id');
//    }

    public function getRoleAttr($value, $data)
    {
        if (empty($data['admin_id'])) {
            return [];
        }
        $info = (new RoleMdl())->field('r.*')->alias('r')
            ->leftJoin('sys_admin_role ar', 'ar.role_id = r.role_id')
            ->where(['ar.admin_id' => $data['admin_id']])->find();
        if (is_null($info)) {
            return [];
        }
        return $info->toArray();
    }

    /**
     * @Author     : lpc
     * @DateTime   : 2021/5/19 12:06
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
     * @Author     : lpc
     * @DateTime   : 2021/5/19 12:04
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
     * @Author     : lpc
     * @DateTime   : 2021/5/19 12:00
     * @Description: 删除管理员
     * @param $adminId
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function delAdmin($adminId): array
    {
        $oldVo = $this->getFieldChangeData($adminId);
        $res1 = $this->where(['admin_id' => $adminId])->delete();
        $res2 = (new AdminRoleMdl())->where(['admin_id' => $adminId])->delete();

        $this->setLogRecordType(self::LOG_RECORD_TYPE_deleted);
        ModelFiledLogService::recordLog($this, $oldVo, $oldVo);
        return ($res1 && $res2) ? Result::serviceSucc() : Result::serviceError('删除失败！');
    }

    /**
     * @Author     : lpc
     * @DateTime   : 2021/5/19 12:01
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
        $info = $this->field($field)->append(['role'])->find($adminId);
        if (is_null($info)) {
            return [];
        }
        return $info->toArray();
    }

    /**
     * @Author     : lpc
     * @DateTime   : 2021/5/17 15:40
     * @Description: 保存管理员用户
     * @param $data
     * @return array
     * @throws Exception
     */
    public function saveAdminUser(&$data): array
    {
        $oldVo = $this->getFieldChangeData(@$data['admin_id']);
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
                $id = $this->strict(false)->insertGetId($data);
                $data['admin_id'] = $id;
            } else {
                $data['update_time'] = time();
                $this->update($data);
                $this->setLogRecordType(self::LOG_RECORD_TYPE_UPDATED);
            }

            //给管理员保存角色
            $adminRoleMdl = new AdminRoleMdl();
            $adminRoleMdl->where(['admin_id' => $data['admin_id']])->delete();
            $adminRoleMdl->insert(['admin_id' => $data['admin_id'], 'role_id' => $data['role_id']]);

            $newVo = $this->getFieldChangeData($data['admin_id']);
            ModelFiledLogService::recordLog($this, $oldVo, $newVo);

            // 提交事务
            Db::commit();
            return Result::serviceSucc();

        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Notes: 获取业务级日志记录所需实体数据
     * Date: 2022/1/21 15:28
     * @param $id
     * @return AdminUserEntity
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author: lpc
     */
    public function getFieldChangeData($id): AdminUserEntity
    {
        $vo = new AdminUserEntity();
        $adminInfo = $this->infoAdmin($id);
        if (!$adminInfo) {
            return $vo;
        }
        $vo->setAvatar($adminInfo['avatar']);
        $vo->setLoginName($adminInfo['login_name']);
        $vo->setRole($adminInfo['role']['role_name'] ?? '');

        $optionalFieldMap = [
            'id' => $id,
            'name' => $adminInfo['login_name']
        ];

        $vo->setOptionalFieldMap($optionalFieldMap);

        return $vo;
    }

    /**
     * Notes: 记录业务级日志
     * Date: 2022/1/22 11:05
     * @param $oldVo
     * @param $content
     * @return void
     * @author: lpc
     */
    public function saveBusinessLog($oldVo, $content)
    {
        $request = app('request');

        $optionalFieldMap = $oldVo->getOptionalFieldMap();

        $adminLogMdl = new AdminLogMdl();
        $adminLogMdl['type'] = $this->getLogRecordType();
        $adminLogMdl['admin_id'] = $optionalFieldMap['id'] ?? '';
        $adminLogMdl['admin_name'] = $optionalFieldMap['name'] ?? '';
        $adminLogMdl['op_user_id'] = $request->adminInfo->admin_id;
        $adminLogMdl['op_user_name'] = $request->adminInfo->login_name;
        $adminLogMdl['content'] = $content;
        $adminLogMdl['created_time'] = date('Y-m-d H:i:s');
        $adminLogMdl->save();
    }
}
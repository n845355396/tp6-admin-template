<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 14:30
 * @Description: 管理员服务类
 * 
 * @return 

 */

namespace app\admin\service;


use app\admin\model\AdminMdl;
use app\admin\model\RoleMdl;
use app\admin\validate\AdminValidate;
use app\common\service\JwtService;
use app\common\utils\Captcha;
use app\common\utils\Result;
use app\common\utils\UserPassword;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\exception\ValidateException;

class AdminService extends BaseService
{
    /**
     * @var AdminMdl
     */
    private $adminMdl;

    public function __construct()
    {
        parent::__construct();
        $this->adminMdl = new AdminMdl();
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 17:22
     * @Description: 管理员列表
     * @param array $where
     * @param $pageData
     * @throws DbException
     */
    public function list(array $where, $pageData): array
    {
        $field = 'a.admin_id,a.login_name,a.status,a.mobile,a.nickname,a.create_time,a.update_time,a.is_super';
        $data  = ($this->adminMdl)->alias('a')->field($field)
            ->with(['role'])
            ->where($where)->paginate($pageData)->toArray();
        return Result::serviceSucc($data);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 17:15
     * @Description: 管理员登录
     * @param $data
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function login($data): array
    {
        try {
            validate(AdminValidate::class)->scene('login')->check($data);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return Result::serviceError($e->getMessage());
        }

        //验证码验证
        if (!invoke(Captcha::class)->check($data['code'])) {
            return Result::serviceError("验证码错误！");
        }

        $adminMdl  = $this->adminMdl;
        $adminInfo = $adminMdl->field('*')->where(['login_name' => $data['login_name']])->find();
        if (is_null($adminInfo)) {
            return Result::serviceError("管理员不存在！");
        }
        $adminInfo = $adminInfo->toArray();

        if (!UserPassword::checkPass($data['password'], $adminInfo['password'])) {
            return Result::serviceError("登录密码错误！");
        }

        //登录验证完成，发放token
        $token = $this->getToken($adminInfo);

        return Result::serviceSucc(['token' => $token]);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 16:40
     * @Description: 生成用户token
     * @param $adminInfo
     * @return string
     */
    private function getToken($adminInfo): string
    {
        return JwtService::getToken($adminInfo);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 12:16
     * @Description: 检测管理员密码是否跟提供的密码一致
     * @param $adminId
     * @param $password
     * @return bool
     */
    public function checkPass($adminId, $password): bool
    {
        $has = $this->adminMdl->where(['admin_id' => $adminId, 'password' => $password])->count();
        return $has == 1;
    }

    public function upPassword($data): array
    {
        try {
            validate(AdminValidate::class)->scene('upPassword')->check($data);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return Result::serviceError($e->getMessage());
        }

        return $this->adminMdl->upPassword($data['admin_id'], $data['password']);
    }

    public function disable($adminId, $status = 0): array
    {
        $isSuper = $this->getAdminType($adminId);
        if ($isSuper) {
            return Result::serviceError('超级管理员不允许操作！');
        }
        $status = empty($status) ? 0 : 1;
        return $this->adminMdl->disableAdmin($adminId, $status);
    }

    public function delete($adminId): array
    {
        $isSuper = $this->getAdminType($adminId);
        if ($isSuper) {
            return Result::serviceError('超级管理员不能禁用！');
        }
        return $this->adminMdl->delAdmin($adminId);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 11:01
     * @Description: 获取管理员信息
     * @param $adminId
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function info($adminId): array
    {
        return $this->adminMdl->infoAdmin($adminId);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/19 10:46
     * @Description: 管理员编辑
     * @param $data
     * @return array
     * @throws Exception
     */
    public function edit($data): array
    {
        try {
            validate(AdminValidate::class)->scene('edit')->check($data);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return Result::serviceError($e->getMessage());
        }
        $adminMdl = $this->adminMdl;
        $saveRes  = $adminMdl->saveAdminUser($data);
        if ($saveRes['status']) {
            return Result::serviceSucc();
        }

        return Result::serviceError($saveRes['msg']);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 14:40
     * @Description: 创建平台管理员
     * @param $data
     * @return array
     * @throws Exception
     */
    public function create($data): array
    {
        try {
            validate(AdminValidate::class)->scene('create')->check($data);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return Result::serviceError($e->getMessage());
        }
        $adminMdl = $this->adminMdl;
        $saveRes  = $adminMdl->saveAdminUser($data);
        if ($saveRes['status']) {
            return Result::serviceSucc();
        }

        return Result::serviceError($saveRes['msg']);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 14:47
     * @Description: 获取管理员类型
     * @param $adminId
     * @return int|mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getAdminType($adminId)
    {
        $info = $this->adminMdl->field('is_super')->find($adminId);

        if (is_null($info)) {
            return 0;
        }
        return $info['is_super'];
    }


    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 14:32
     * @Description: 获取用户角色信息
     * @param $adminId
     * @return array
     */
    public function getAdminRoleInfo($adminId): array
    {
        $roleMdl = new RoleMdl();
        $info    = $roleMdl->field('r.role_id,r.role_name,r.status')->alias('r')
            ->leftJoin('sys_admin_role ar', 'ar.role_id = r.role_id')
            ->where(['r.status' => 0, 'ar.admin_id' => $adminId])->find();

        if (is_null($info)) {
            return Result::serviceError("管理员角色不存在！");
        }
        return Result::serviceSucc($info->toArray());
    }

}








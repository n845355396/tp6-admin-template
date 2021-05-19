<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 14:45
 * @Description: Description
 * 
 * @return 

 */

namespace app\admin\Validate;


use app\admin\model\AdminMdl;
use app\admin\model\RoleMdl;
use think\Validate;

class AdminValidate extends Validate
{
    private $adminMdl;

    public function __construct()
    {
        parent::__construct();
        $this->adminMdl = new AdminMdl();
    }

    protected $rule = [
        'login_name' => 'require|unique:sys_admin|status',
        'password'   => 'require',
        'nickname'   => 'require',
        'mobile'     => 'require',
        'is_super'   => 'isSuper',
        'code'       => 'require',
        'role_id'    => 'require|roleStatus',
        'admin_id'   => 'require|editLoginNameCheck',

    ];

    protected $message = [
        'login_name.require'          => '管理员账号必填!',
        'password.require'            => '密码必填!',
        'is_super.isSuper'            => '超级管理员唯一!',
        'code.require'                => '验证码必填!',
        'role_id.require'             => '角色必填!',
        'role_id.roleStatus'          => '角色已被禁用!',
        'admin_id.require'            => '管理员id必填!',
        'admin_id.editLoginNameCheck' => '管理员名称已存在!',
        'login_name.status'           => '管理员已禁用!',
    ];

    public function editLoginNameCheck($value, $rule, $data = []): bool
    {
        $has = $this->adminMdl->where([['admin_id', '<>', $value], ['login_name', '=', $data['login_name']]])->count();
        return $has <= 0;
    }

    public function roleStatus($value): bool
    {
        $has = (new RoleMdl())->where(['role_id' => $value, 'status' => 1])->count();
        return $has <= 0;
    }


    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 15:15
     * @Description: 是否唯一超级管理员
     * @param $value
     * @return bool
     */
    public function isSuper($value): bool
    {
        if ($value == 1) {
            $has = $this->adminMdl->where(['is_super' => 1])->count();
            return $has <= 0;
        }
        return true;
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 15:15
     * @Description: 状态
     * @param $value
     * @return bool
     */
    public function status($value): bool
    {
        $has = $this->adminMdl->where(['status' => 1, 'login_name' => $value])->count();
        return $has <= 0;
    }

    public function sceneUpPassword(): AdminValidate
    {
        return $this->only(['admin_id', 'password'])
            ->remove('admin_id', 'editLoginNameCheck');
    }


    public function sceneLogin(): AdminValidate
    {
        return $this->only(['login_name', 'password', 'code'])
            ->remove('login_name', 'unique');
    }

    public function sceneEdit(): AdminValidate
    {
        return $this->only(['admin_id', 'login_name', 'is_super', 'role_id'])
            ->remove('login_name', 'status')
            ->remove('login_name', 'unique');
    }

    public function sceneCreate(): AdminValidate
    {
        return $this->only(['login_name', 'password', 'is_super', 'role_id'])
            ->remove('login_name', 'status');
    }
}
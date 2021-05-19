<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/19 13:19
 * @Description: Description
 * 
 * @return 

 */

namespace app\admin\Validate;


use app\admin\model\AdminRoleMdl;
use app\admin\model\RoleMdl;
use think\Validate;

class RoleValidate extends Validate
{
    private $mdl;

    public function __construct()
    {
        parent::__construct();
        $this->mdl = new RoleMdl();
    }

    protected $rule = [
        'role_name' => 'require|unique:sys_role',
        'role_id'   => 'editRoleNameCheck',
    ];

    protected $message = [
        'role_name.require'         => '角色名必填!',
        'role_id.editRoleNameCheck' => '角色已存在!',
    ];

    public function editRoleNameCheck($value, $rule, $data = []): bool
    {
        $has = $this->mdl->where([['role_id', '<>', $value], ['role_name', '=', $data['role_name']]])->count();
        return $has <= 0;
    }

    public function sceneEdit(): RoleValidate
    {
        return $this->only(['role_name'])
            ->remove('role_name', 'unique');
    }

    public function sceneCreate(): RoleValidate
    {
        return $this->only(['role_name']);
    }
}
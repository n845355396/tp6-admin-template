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
use think\facade\Db;
use think\Validate;

class AdminValidate extends Validate
{
    protected $rule = [
        'login_name' => 'require|unique:sys_admin',
        'password'   => 'require',
        'nickname'   => 'require',
        'mobile'     => 'require',
        'is_super'   => 'isSuper',
        'code'       => 'require',

    ];

    protected $message = [
        'login_name.require' => '管理员账号必填!',
        'password.require'   => '密码必填!',
        'is_super.isSuper'   => '超级管理员唯一!',
        'code.require'       => '验证码必填!',
    ];

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 15:15
     * @Description: 模板
     * @return bool
     */
    public function isSuper(): bool
    {
        $has = (new AdminMdl())->where(['is_super' => 1])->count();
        return $has <= 0;
    }

    public function sceneLogin(): AdminValidate
    {
        return $this->only(['login_name', 'password', 'code'])
            ->remove('login_name', 'unique');;
    }

    public function sceneCreate(): AdminValidate
    {
//        return $this->only(['login_name', 'password'])
//            ->remove('login_name', 'unique');
        return $this->only(['login_name', 'password', 'is_super']);
    }
}
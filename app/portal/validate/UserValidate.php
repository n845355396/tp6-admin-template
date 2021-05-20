<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/20 13:04
 * @Description: Description
 * 
 * @return 

 */

namespace app\portal\validate;


use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'username'   => 'require|unique:user',
        'password'   => 'require',
        'login_type' => 'require|in:password,sms,wx',
    ];

    protected $message = [
        'username.require'   => '登录账号必填!',
        'password.require'   => '密码必填!',
        'login_type.require' => '登录类型必填!',
        'login_type.in'      => '登录类型格式错误!',
    ];

    public function sceneLogin(): UserValidate
    {
        return $this->only(['username', 'password','login_type'])
            ->remove('username', 'unique');
    }
}
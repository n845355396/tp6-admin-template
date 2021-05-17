<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 16:07
 * @Description: 登录密码验证类
 * 
 * @return 

 */

namespace app\common\utils;


class UserPassword
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 16:09
     * @Description: 登录密码加密
     * @param $password
     * @return false|string|null
     */
    public static function encrypt($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 16:09
     * @Description: 验证用户密码
     * @param $password
     * @return false
     */
    public static function checkPass($password, $hash): bool
    {
        if (password_verify($password, $hash)) {
            //验证密码是否和散列值匹配
            return true;
        } else {
            return false;
        }
    }
}
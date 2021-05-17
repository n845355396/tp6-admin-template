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
use app\admin\Validate\AdminValidate;
use app\common\service\JwtService;
use app\common\utils\Captcha;
use app\common\utils\Result;
use app\common\utils\UserPassword;
use think\exception\ValidateException;

class AdminService
{
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

        $adminMdl  = new AdminMdl();
        $adminInfo = $adminMdl->field('*')->where(['login_name' => $data['login_name']])->find();
        if (is_null($adminInfo)) {
            return Result::serviceError("管理员不存在！");
        }
        $adminInfo = $adminInfo->toArray();

        if (!UserPassword::checkPass($data['password'], $adminInfo['password'])) {
            return Result::serviceError("登录密码错误！");
        }

        //登录验证完成，发放token
        $token = $this->getToken($adminInfo['admin_id']);

        return Result::serviceSucc(['token' => $token]);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 16:40
     * @Description: 生成用户token
     * @param $userId
     * @return string
     */
    private function getToken($userId): string
    {
        //lpc token里将带上用户信息，以及后台菜单权限
        //todo 先只给一个id吧
        $data['adminId'] = $userId;
        return JwtService::getToken($data);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 14:40
     * @Description: 创建平台管理员
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function create($data): array
    {
        try {
            validate(AdminValidate::class)->scene('create')->check($data);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return Result::serviceError($e->getMessage());
        }
        $adminMdl = new AdminMdl();
        $saveRes  = $adminMdl->saveAdminUser($data);
        if ($saveRes['status']) {
            return Result::serviceSucc();
        }
        return Result::serviceError($saveRes['msg']);
    }
}
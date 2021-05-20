<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/20 12:54
 * @Description: Description
 * 
 * @return 

 */

namespace app\portal\service;


use app\common\model\UserMdl;
use app\common\service\JwtService;
use app\common\utils\Captcha;
use app\common\utils\Result;
use app\common\utils\UserPassword;
use app\portal\validate\UserValidate;
use think\exception\ValidateException;

class UserService extends BaseService
{
    /**
     * @var UserMdl
     */
    private $userMdl;

    public function __construct()
    {
        parent::__construct();
        $this->userMdl = new UserMdl();
    }

    public function login($data): array
    {
        try {
            validate(UserValidate::class)->scene('login')->check($data);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return Result::serviceError($e->getMessage());
        }
        $res = [];
        switch ($data['login_type']) {
            case 'password':
                $res = $this->passwordLogin($data);
                break;
            case 'sms':
                $res = $this->smsLogin($data);
                break;
            case 'wx':
                $res = $this->wxLogin($data);
                break;
        }
        if (!$res || !$res['status']) {
            return $res;
        }

        //登录验证完成，发放token
        $token = $this->getToken($res['data']);

        return Result::serviceSucc(['token' => $token]);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/20 12:58
     * @Description: 发放好人卡
     * @param $userInfo
     * @return string
     */
    private function getToken($userInfo): string
    {
        return JwtService::getToken($userInfo);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/20 13:15
     * @Description: 密码登录
     * @param $data
     */
    private function passwordLogin($data): array
    {
        $adminMdl = $this->userMdl;
        $userInfo = $adminMdl->field('*')->where(['username' => $data['username']])->find();
        if (is_null($userInfo)) {
            return Result::serviceError("用户不存在！");
        }
        $userInfo = $userInfo->toArray();

        if (!UserPassword::checkPass($data['password'], $userInfo['password'])) {
            return Result::serviceError("登录密码错误！");
        }
        return Result::serviceSucc($userInfo);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/20 13:16
     * @Description: 短信登录
     * @param $data
     * @return array
     */
    private function smsLogin($data): array
    {
        return Result::serviceError("暂不支持！");
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/20 13:16
     * @Description: 微信信任登录
     * @param $data
     * @return array
     */
    private function wxLogin($data): array
    {
        return Result::serviceError("暂不支持！");
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/20 13:33
     * @Description: 检查密码是否相同
     * @param $userId
     * @param $password
     * @return bool
     */
    public function checkPass($userId, $password): bool
    {
        $has = $this->userMdl->where(['user_id' => $userId, 'password' => $password])->count();
        return $has == 1;
    }
}
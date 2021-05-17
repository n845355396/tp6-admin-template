<?php


namespace app\common\service;


use app\common\utils\Result;
use Exception;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;


class JwtService
{
    private const KEY = "!@#$%*&cqkyi";

    /**
     * @param $data
     * 生成token
     * api后台接口使用
     */
    public static function getToken($data): string
    {

        $token = [
            "iss"  => self::KEY,
            "aud"  => '',          //面象的用户，可以为空
            "iat"  => time(),      //签发时间
            "nbf"  => time() + 3,    //在什么时候jwt开始生效  （这里表示生成3秒后才生效）
            "exp"  => time() + 604800,//token的有效期,一个星期（60*60*24*7）=604800
            "data" => $data
        ];
        return JWT::encode($token, self::KEY, "HS256");
    }

    public static function checkToken($token)
    {
        try {
            JWT::$leeway = 60;//当前时间减去60，把时间留点余地
            $decoded     = JWT::decode($token, self::KEY, array('HS256')); //HS256方式，这里要和签发的时候对应
            $arr         = (array)$decoded;
            return Result::serviceSucc($arr);

        } catch (SignatureInvalidException $e) {
            return Result::serviceError('签名不正确');
        } catch (BeforeValidException $beforeValidException) {
            return Result::serviceError('token失效');
        } catch (ExpiredException $expiredException) {
            return Result::serviceError('token过期');
        } catch (Exception $exception) {
            return Result::serviceError('未知错误');
        }

    }

}
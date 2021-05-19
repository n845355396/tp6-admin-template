<?php


namespace app\common\utils;


use think\response\Json;

class Result
{
    const OK = 200;//成功
    const ERROR = 201;//失败
    const TOKEN_ERROR = 401;//失败
    const NO_PERMISSION = 403;//权限不足
    const NO_FOUND = 404;//没发现

    /**
     * @Author: lpc
     * @DateTime: 2021/5/7 16:45
     * @Description: 处理service层返回的固定格式参数返回
     * @param $res
     */
    public static function disposeServiceRes($res): Json
    {
        $msg  = empty($res['msg']) ? "操作失败" : $res['msg'];
        $data = empty($res['data']) ? [] : $res['data'];
        if ($res['status']) {
            return self::succ($data, $msg);
        }
        return self::error($msg, $data);
    }

    /**
     * @Author: lpc
     * @DateTime: 2020/11/13 14:20
     * @Description: 接口请求响应正确返回
     * @param array $data : 数据
     * @param string $msg : 描述
     * @return Json
     */
    public static function succ(array $data = [], string $msg = "操作成功", $code = self::OK): Json
    {
        $result = [
            'code' => $code, // 业务状态码
            'msg'  => $msg,
            'data' => $data
        ];
        return json($result);
    }

    /**
     * @Author: lpc
     * @DateTime: 2020/11/13 14:20
     * @Description: 接口请求响应错误返回
     * @param string $msg : 描述
     * @param array $data : 数据
     * @return Json
     */
    public static function error(string $msg = "操作失败！", $code = self::ERROR, array $data = []): Json
    {
        $result = [
            'code' => $code, // 业务状态码
            'msg'  => $msg,
            'data' => $data
        ];
        return json($result);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 13:02
     * @Description: 业务逻辑成功返回
     * @param array $data : 数据
     * @param string $msg : 描述
     * @return array
     */
    public static function serviceSucc(array $data = [], string $msg = "操作成功"): array
    {
        return [
            'status' => true,
            'msg'    => $msg,
            'data'   => $data
        ];
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 13:02
     * @Description: 业务逻辑失败返回
     * @param string $msg : 描述
     * @param array $data : 数据
     * @return array
     */
    public static function serviceError(string $msg = "操作失败", array $data = []): array
    {
        return [
            'status' => false,
            'msg'    => $msg,
            'data'   => $data
        ];
    }

}
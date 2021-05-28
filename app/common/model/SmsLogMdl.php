<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/27 11:23
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\model;


use think\facade\Config;

class SmsLogMdl extends BaseModel
{
    protected $table = 'sms_log';
    protected $pk = 'sms_id';

    const WAITING = 'waiting';//等待发送中
    const SUCCESS = 'success';//发送成功
    const FAILED = 'failed';//发送失败


    public function getRetryTimeAttr($value)
    {
        if (!$value) {
            return '';
        }
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/27 12:02
     * @Description: 保存日志
     * @param $sendMode :短信模式
     * @param $type :短信模板
     * @param $mobile :手机号
     * @param $requestData :请求数据
     * @param $resultData :结果数据
     * @param string $status :状态
     * @return int
     */
    public static function addLog($sendMode, $type, $mobile, $requestData, $resultData, string $status = self::WAITING): int
    {
        $tempArr = Config::get('sms.sms_template');
        $nowTime = time();
        $data    = [
            'send_mode'    => $sendMode,
            'type'         => $type,
            'type_name'    => $tempArr[$type]['name'],
            'mobile'       => $mobile,
            'request_data' => json_encode($requestData, JSON_UNESCAPED_UNICODE),
            'result_data'  => json_encode($resultData, JSON_UNESCAPED_UNICODE),
            'status'       => $status,
            'create_time'  => $nowTime,
            'update_time'  => $nowTime,
        ];
        return self::insertGetId($data);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/27 12:01
     * @Description: 更新短信日志状态
     * @param $smsId :日志id
     * @param $status :更新状态
     * @param $resultData :结果数据
     * @return bool
     */
    public static function upStatus($smsId, $status, $resultData): bool
    {
        $data = [
            'status'      => $status,
            'result_data' => json_encode($resultData, JSON_UNESCAPED_UNICODE),
            'update_time' => time(),
        ];

        return self::where(['sms_id' => $smsId])->save($data);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/27 12:01
     * @Description: 重发日志
     * @param $smsId
     * @return bool
     */
    public static function retryLog($smsId): bool
    {
        $data = [
            'is_retry'   => 1,
            'retry_time' => time(),
        ];
        return self::where(['sms_id' => $smsId])->save($data);
    }

    public function list($where, $pageData, $orderBy = 'update_time desc'): array
    {
        $field = '*';
        return $this->field($field)->where($where)
            ->order($orderBy)->paginate($pageData)->toArray();
    }

}














<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/27 11:07
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\task;


use app\common\model\SmsLogMdl;
use app\common\utils\SmsUtil;
use Exception;

class SendSmsTask extends TaskBase implements TaskInterface
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/24 17:54
     * @Description: 任务执行方法
     * @param $data : 执行数据
     * @return bool
     * @throws \Exception
     */
    public function handle($data): bool
    {
        try {
            $paramsData = $data['data'];
            $res        = SmsUtil::send($paramsData['mobile'],
                $paramsData['template'],
                $paramsData['data'],
                $paramsData['mode']);

            $smsStatus = $res['status'] ? SmsLogMdl::SUCCESS : SmsLogMdl::FAILED;

            //更新短信日志
            SmsLogMdl::upStatus($paramsData['sms_log_id'], $smsStatus, $res['data']);

            return $res['status'];

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
















<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/27 10:26
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\service;


use app\common\model\SmsLogMdl;
use app\common\task\SendSmsTask;
use app\common\utils\queue\QueueParamsDto;
use app\common\utils\Result;
use app\common\utils\SmsUtil;
use app\common\utils\TaskUtil;
use Exception;
use Qiniu\Sms\Sms;

class SmsService extends BaseService
{
    /**
     * @var SmsLogMdl
     */
    private $smsLogMdl;

    public function __construct()
    {
        parent::__construct();
        $this->smsLogMdl = new SmsLogMdl();
    }

    public function list($where, $pageData): array
    {
        return $this->smsLogMdl->list($where, $pageData);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/27 10:29
     * @Description: 直接发送短信
     * @param $mobile :手机号
     * @param $template :短信模板
     * @param $data :数据
     * @param string $mode :模式
     * @return mixed
     * @throws Exception
     */
    public function send($mobile, $template, array $data, string $mode = SmsUtil::DIY_MODE)
    {
        $res       = SmsUtil::send($mobile,
            $template,
            $data,
            $mode);
        $smsStatus = $res['status'] ? SmsLogMdl::SUCCESS : SmsLogMdl::FAILED;
        //添加短信发送日志
        SmsLogMdl::addLog($mode, $template, $mobile, $data, $res['data'], $smsStatus);
        return $res;
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/27 11:05
     * @Description: 短信添加到队列立即执行
     * @param $mobile :手机号
     * @param $template :短信模板
     * @param array $data :数据
     * @param string $mode :短信模式
     * @param int $delayTime 延时队列发送，小于等于0则直接入队列执行
     */
    public function sendToQueue($mobile, $template, array $data, int $delayTime = 0, string $mode = SmsUtil::DIY_MODE)
    {
        //因为要跑队列短信状态会有个waiting过程
        //我怕在下面加日志会导致队列执行完成了，日志还没添加的问题
        //所以先给日志写上
        $smsStatus = SmsLogMdl::WAITING;
        $logId     = SmsLogMdl::addLog($mode, $template, $mobile, $data, '', $smsStatus);

        $dto     = new QueueParamsDto();
        $smsData = [
            'sms_log_id' => $logId,
            'mobile'     => $mobile,
            'template'   => $template,
            'data'       => $data,
            'mode'       => $mode
        ];
        $dto->setData($smsData);
        $dto->setTaskClass(SendSmsTask::class);
        return Kernel::single(TaskService::class)->publish($dto, $delayTime);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/27 12:22
     * @Description: 短信重发
     * @param int $smsLogId : 短信日志id
     * @param string $sendType :发送方式，normal立即发送，queue走队列
     * @param int $delayTime :延时发送，若选择queue，此参数生效
     */
    public function smsRetry(int $smsLogId, string $sendType = 'normal', int $delayTime = 0)
    {
        $smsLogMdl = new SmsLogMdl();
        //先拿到需要重发短信的数据
        $smsInfo = $smsLogMdl->where(['sms_id' => $smsLogId])->find();
        if (is_null($smsInfo)) {
            return Result::serviceError('短信日志不存在！');
        }
        if ($smsInfo['is_retry']) {
            return Result::serviceError('短信已重发！');
        }
        $data = json_decode($smsInfo['request_data'], true);
        //拿到数据后组织短信发送
        if ($sendType == 'normal') {
            $res = $this->send($smsInfo['mobile'], $smsInfo['type'], $data, $smsInfo['send_mode']);
        } else {
            $res = $this->sendToQueue($smsInfo['mobile'], $smsInfo['type'], $data, $delayTime, $smsInfo['send_mode']);
        }
        //发送后无论成功与否，原日志记录已重发操作
        SmsLogMdl::retryLog($smsLogId);
        //返回结果
        return $res;
    }


}




















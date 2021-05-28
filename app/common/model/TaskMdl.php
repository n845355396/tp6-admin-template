<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/25 10:28
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\model;


use app\common\utils\queue\QueueParamsDto;

class TaskMdl extends BaseModel
{
    protected $table = 'sys_task';
    protected $pk = 'task_id';

    //日志状态结果类目 start  waiting,retrying,success,failed
    const WAITING = 'waiting';
    const RETRYING = 'retrying';
    const SUCCESS = 'success';
    const FAILED = 'failed';

    //日志状态结果类目 end

    public static function upLog(string $uniqueCode, string $result, int $retryNum = 0)
    {
        $nowTime = time();
        $upData  = [
            'result'      => $result,
            'update_time' => $nowTime
        ];
        if ($retryNum > 0) {
            $upData['retry_num'] = $retryNum;
        }
        (new TaskMdl)->where(['unique_code' => $uniqueCode])->update($upData);
    }

    public static function log(QueueParamsDto $dto)
    {
        $nowTime  = time();
        $saveData = [
            'unique_code'  => $dto->getUniqueCode(),
            'queue_name'   => $dto->getQueueName(),
            'task_name'    => $dto->getTaskClass(),
            'request_data' => json_encode($dto->getData()),
            'result'       => self::WAITING,
            'create_time'  => $nowTime,
            'update_time'  => $nowTime
        ];

        (new TaskMdl)->insert($saveData);
    }

    public function list($where, $pageData, $orderBy = 'update_time desc'): array
    {
        $field = '*';
        return $this->field($field)->where($where)
            ->order($orderBy)->paginate($pageData)->toArray();
    }

}
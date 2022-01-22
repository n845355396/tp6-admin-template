<?php
/*
 * @Author: lpc
 * @DateTime: 2022/1/22 13:27
 * @Notes: Description
 * 
 * @return 

 */

namespace app\common\service;

use app\common\model\BaseModel;

class ModelFiledLogService
{
    private static array $filterFieldArr = ['fieldNameMap', 'optionalFieldMap'];

    public static function recordLog($model, $oldVo, $newVo)
    {
        $oldData = json_decode(json_encode($oldVo), true);
        $newData = json_decode(json_encode($newVo), true);

        $resVo = $oldVo;
        $fieldNameMap = $oldData['fieldNameMap'];

        $content = '';
        switch ($model->getLogRecordType()) {
            case BaseModel::LOG_RECORD_TYPE_UPDATED:
                $contentArr = [];
                foreach ($oldData as $field => $oldVal) {
                    if (!in_array($field, self::$filterFieldArr)) {
                        $newVal = $newData[$field];
                        if ($oldVal != $newVal) {
                            $contentArr[] = "【字段:{$fieldNameMap[$field]} 由 [{$oldVal}] 修改为 [{$newVal}]】";
                        }
                    }
                }
                $content = implode(',', $contentArr);
                break;
            case BaseModel::LOG_RECORD_TYPE_CREATED:
                $resVo = $newVo;
                $content = '创建了该记录';
                break;
            case BaseModel::LOG_RECORD_TYPE_deleted:
                $content = '删除了该记录';
                break;
            default:
                break;
        }

        if ($content) {
            $model->saveBusinessLog($resVo, $content);
        }
    }
}
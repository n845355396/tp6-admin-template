<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 15:46
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\model;


use think\Model;

class BaseModel extends Model
{
    public function getCreateTimeAttr($value)
    {
        if (!$value) {
            return '';
        }
        return date('Y-m-d H:i:s', $value);
    }

    public function getUpdateTimeAttr($value)
    {
        if (!$value) {
            return '';
        }
        return date('Y-m-d H:i:s', $value);
    }
}
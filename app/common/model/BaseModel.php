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

    /**
     * @Author: lpc
     * @DateTime: 2021/7/30 19:00
     * @Description: 组装service层的where数据使其成为一个tp能用的where数组,多表字段查询请自行解决
     * @param $tmpData : 字段字段组装类型
     * @param $data : 数组
     */
    public function assemblyWhereData($tmpData, $data, $alias = null)
    {
        $where    = [];
        $fieldArr = [];

        foreach ($tmpData as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $vv) {
                    $fieldArr[$vv] = $key;
                }
            } else {
                $fieldArr[$value] = $key;
            }
        }

        foreach ($data as $field => $value) {
            $aliasField = $alias ? "$alias.$field" : $field;
            switch (@$fieldArr[$field]) {
                //in
                case 'in':
                    //不等于
                case 'neq':
                    //大于
                case  'gt':
                    //大于等于
                case  'egt':
                    //小于
                case  'lt':
                    //小于等于
                case  'elt':
                    $where[] = [$aliasField, $fieldArr[$field], $value];
                    break;
                case  'like':
                    $where[] = [$aliasField, $fieldArr[$field], "%$value%"];
                    break;
                default:
                    $where[] = [$aliasField, '=', $value];
                    break;
            }
        }
        return $where;
    }

}
<?php
/*
 * @Author: lpc
 * @DateTime: 2022/1/21 15:24
 * @Notes: Description
 * 
 * @return 

 */

namespace app\admin\logEntity;

class BaseLogEntity
{
    /**
     * 参数映射的中文名
     * @var array
     */
    protected array $fieldNameMap = [];

    /**
     * 日志表需要的字段都可以存这里
     * @var array|string[]
     */
    protected array $optionalFieldMap = [
        'id' => '',
        'name' => ''
    ];


    public function getOptionalFieldMap()
    {
        return $this->optionalFieldMap;
    }

    /**
     * @param array|string[] $optionalFieldMap
     */
    public function setOptionalFieldMap(array $optionalFieldMap): void
    {
        $this->optionalFieldMap = $optionalFieldMap;
    }

    /**
     * @return array
     */
    public function getFieldNameMap(): array
    {
        return $this->fieldNameMap;
    }

    /**
     * @param array $fieldNameMap
     */
    public function setFieldNameMap(array $fieldNameMap): void
    {
        $this->fieldNameMap = $fieldNameMap;
    }
}
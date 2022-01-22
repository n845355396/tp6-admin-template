<?php
/*
 * @Author: lpc
 * @DateTime: 2022/1/21 17:52
 * @Notes: 实现业务级日志接口
 * 
 * @return 

 */

namespace app\admin\model;

use app\admin\logEntity\BaseLogEntity;

interface ModelLogInterface
{
    /**
     * Notes: 获取业务级日志记录所需实体数据
     * Date: 2022/1/21 17:53
     * @param $id
     * @return mixed
     * @author: lpc
     */
    public function getFieldChangeData($id);

    /**
     * Notes: 记录业务级日志
     * Date: 2022/1/22 11:05
     * @param $oldVo
     * @param $content
     * @return mixed
     * @author: lpc
     */
    public function saveBusinessLog($oldVo,$content);

}
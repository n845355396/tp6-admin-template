<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 12:11
 * @Description: 获取单例服务类,弃用
 * 
 * @return 

 */

namespace app\common\service;


class Kernel extends BaseService
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 14:19
     * @Description: 通过容器获取服务实例
     * @param $class_name :类名或者标识
     * @param array|null $arg :变量
     * @param bool $newInstance :是否每次创建新的实例
     * @return mixed
     */
    public static function single($class_name, array $arg = [], bool $newInstance = false)
    {
        return app()->make($class_name, $arg, $newInstance);
    }


    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 12:46
     * @Description: 获取当前容器实例迭代器
     * @return \ArrayIterator
     */
    public static function getSingletonInstance()
    {
        return app()->getIterator();
    }


}
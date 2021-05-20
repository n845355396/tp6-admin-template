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
    private static $__singleton_instance = [];


    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 14:19
     * @Description: Description
     * @param $class_name :类名或者标识
     * @param array|null $arg :变量
     * @param bool $newInstance :是否每次创建新的实例
     * @return mixed
     */
    public static function single($class_name, array $arg = [], bool $newInstance = false)
    {
        //lpc 我为什么要这样包一层呢是因为傻逼嘛，不是，因为这样我觉得很酷(主要还是 app()->make()太难写了)
        return app()->make($class_name, $arg, $newInstance);
        //@Author: lpc @Description: 下面是自己写的单例，后来发现tp有自带，替换下吧 @DateTime: 2021/5/17 15:18
//        if (is_object($arg)) {
//            //lpc 如果构造参数是个对象
//            $key = get_class($arg);
//            $key = '__class__' . $key;
//        } else {
//            //lpc 构造参数不是对象，就是空要么一个个参数
//            $key = md5('__key__' . serialize($arg));
//        }
//        if (!isset(self::$__singleton_instance[$class_name][$key])) {
//            if (is_object($arg)) {
//                self::$__singleton_instance[$class_name][$key] = new $class_name($arg);
//            } elseif (is_array($arg)) {
//                self::$__singleton_instance[$class_name][$key] = new $class_name(...$arg);
//            } else {
//                self::$__singleton_instance[$class_name][$key] = new $class_name();
//            }
//        }
//        return self::$__singleton_instance[$class_name][$key];
    }


    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 12:46
     * @Description: 获取当前单例对象数组
     * @return array|\ArrayIterator
     */
    public static function getSingletonInstance()
    {
        return app()->getIterator();
//        return self::$__singleton_instance;
    }


}
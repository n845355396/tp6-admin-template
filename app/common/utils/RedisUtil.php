<?php
/*
 * @Author: lpc
 * @DateTime: 2021/6/1 18:41
 * @Description: redis工具类
 * @return
 */

namespace app\common\utils;

use Exception;
use Redis;
use think\App;
use think\facade\Config;

/**
 * redis操作类
 * 说明，任何为false的串，存在redis中都是空串。
 * 只有在key不存在时，才会返回 false
 * 这点可用于防止缓存穿透
 */
class RedisUtil
{
    private $redis;

    //当前数据库ID号
    protected $dbId = 0;

    //当前权限认证码
    protected $auth;

    private static $redisSingle;

    // host
    protected $host;

    // 端口
    protected $port;


    private function __construct()
    {
        try {
            //获取tp缓存redis配置信息
            $config = Config::get('cache.stores.redis');

            //new一个redis对象
            $this->redis = new Redis;
            //给工具类属性赋值
            $this->port = !empty($config['port']) ? $config['port'] : 6379;
            $this->host = !empty($config['host']) ? $config['host'] : '127.0.0.1';
            $this->dbId = empty($config['select']) ? 0 : $config['select'];
            $timeout    = empty($config['timeout']) ? 0 : $config['timeout'];
            $this->auth = empty($config['password']) ? '' : $config['password'];
            //连接redis
            $this->redis->connect($this->host, $this->port, $timeout);

            if ($this->auth) {
                $authRes = $this->auth($this->auth);
                if (!$authRes) {
                    throw new Exception('redis验证失败！');
                }
            }
            //给redis选定数据库
            $this->select($this->dbId);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/6/2 12:27
     * @Description: 实例化一个redis工具类
     * @return RedisUtil
     */
    public static function getInstance(): RedisUtil
    {
        if (!self::$redisSingle) {
            self::$redisSingle = new self();
        }
        return self::$redisSingle;
    }

    private function __clone()
    {
    }

    /**
     * getRedis 执行原生的redis操作
     * Created by PhpStorm.
     * User: w
     * Date: 2018-12-09
     * Time: 12:35
     * @return Redis
     */
    public function getRedis(): Redis
    {
        return $this->redis;
    }

//region hash表操作函数->hash

    /*****************hash表操作函数*******************/

    /**
     * hGet 得到hash表中一个字段的值
     * @param string $key 缓存key
     * @param string $field 字段
     * @return string string|false
     */
    public function hGet(string $key, string $field): string
    {
        return $this->redis->hGet($key, $field);
    }

    /**
     * 为hash表设定一个字段的值
     * @param string $key 缓存key
     * @param string $field 字段
     * @param string $value 值。
     * @return  int|bool:失败返回false
     */
    public function hSet(string $key, string $field, string $value)
    {
        return $this->redis->hSet($key, $field, $value);
    }

    /**
     * hExists 判断hash表中，指定field是不是存在
     * @param string $key 缓存key
     * @param string $field 字段
     * @return bool
     */
    public function hExists($key, $field)
    {
        return $this->redis->hExists($key, $field);
    }

    /**
     * hDel 删除hash表中指定字段 ,支持批量删除
     * @param string $key 缓存key
     * @param string $field 字段
     * @return int
     */
    public function hDel($key, $field)
    {
        $fieldArr = explode(',', $field);
        $delNum   = 0;

        foreach ($fieldArr as $row) {
            $row    = trim($row);
            $delNum += $this->redis->hDel($key, $row);
        }

        return $delNum;
    }

    /**
     * hLen 返回hash表元素个数
     * @param string $key 缓存key
     * @return int|bool
     */
    public function hLen($key)
    {
        return $this->redis->hLen($key);
    }

    /**
     * hSetNx 为hash表设定一个字段的值,如果字段存在，返回false
     * @param string $key 缓存key
     * @param string $field 字段
     * @param string $value 值。
     * @return bool
     */
    public function hSetNx($key, $field, $value)
    {
        return $this->redis->hSetNx($key, $field, $value);
    }

    /**
     * hMset 为hash表多个字段设定值。
     * @param string $key
     * @param array $value
     * @return array|bool
     */
    public function hMset($key, $value)
    {
        if (!is_array($value))
            return false;
        return $this->redis->hMset($key, $value);
    }

    /**
     * hMget 为hash表多个字段设定值。
     * @param string $key
     * @param array|string $value string以','号分隔字段
     * @return array|bool
     */
    public function hMget($key, $value)
    {
        if (!is_array($value))
            $value = explode(',', $value);
        return $this->redis->hMget($key, $value);
    }

    /**
     * hIncrBy 为hash表设这累加，可以负数
     * @param string $key
     * @param int $field
     * @param string $value
     * @return bool
     */
    public function hIncrBy($key, $field, $value)
    {
        $value = intval($value);
        return $this->redis->hIncrBy($key, $field, $value);
    }

    /**
     * hKeys 返回所有hash表的所有字段
     * @param string $key
     * @return array|bool
     */
    public function hKeys($key)
    {
        return $this->redis->hKeys($key);
    }

    /**
     * hVals 返回所有hash表的字段值，为一个索引数组
     * @param string $key
     * @return array|bool
     */
    public function hVals($key)
    {
        return $this->redis->hVals($key);
    }

    /**
     * hGetAll 返回所有hash表的字段值，为一个关联数组
     * @param string $key
     * @return array|bool
     */
    public function hGetAll($key)
    {
        return $this->redis->hGetAll($key);
    }

    //endregion

//region 有序集合操作->zset

    /*********************有序集合操作*********************/

    /**
     * zAdd 给当前集合添加一个元素
     * 如果value已经存在，会更新order的值。
     * @param string $key
     * @param string $order 序号
     * @param string $value 值
     * @return bool
     */
    public function zAdd($key, $order, $value)
    {
        return $this->redis->zAdd($key, $order, $value);
    }

    /**
     * zincrby 给$value成员的order值，增加$num,可以为负数
     * @param string $key
     * @param string $num 序号
     * @param string $value 值
     * @return mixed 返回新的order
     */
    public function zincrby($key, $num, $value)
    {
        return $this->redis->zincrby($key, $num, $value);
    }

    /**
     * zRem 删除值为value的元素
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function zRem($key, $value)
    {
        return $this->redis->zRem($key, $value);
    }

    /**
     * zRange 集合以order递增排列后，0表示第一个元素，-1表示最后一个元素
     * @param string $key
     * @param int $start
     * @param int $end
     * @return mixed array|bool
     */
    public function zRange($key, $start, $end)
    {
        return $this->redis->zRange($key, $start, $end);
    }

    /**
     * zRevRange 集合以order递减排列后，0表示第一个元素，-1表示最后一个元素
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array|bool
     */
    public function zRevRange($key, $start, $end)
    {
        return $this->redis->zRevRange($key, $start, $end);
    }

    /**
     * 集合以order递增排列后，返回指定order之间的元素。
     * min和max可以是-inf和+inf　表示最大值，最小值
     * @param string $key
     * @param int $start '-inf'
     * @param int $end "+inf"
     * @param array $option 参数 package
     *     withscores=>true，表示数组下标为Order值，默认返回索引数组
     *     limit=>array(0,1) 表示从0开始，取一条记录。
     * @return array|bool
     */
    public function zRangeByScore($key, $start = 0, $end = 0, $option = [])
    {
        return $this->redis->zRangeByScore($key, $start, $end, $option);
    }

    /**
     * 集合以order递减排列后，返回指定order之间的元素。
     * min和max可以是-inf和+inf　表示最大值，最小值
     * @param string $key
     * @param int $start '-inf'
     * @param int $end "+inf"
     * @param array $option 参数 package
     *     withscores=>true，表示数组下标为Order值，默认返回索引数组
     *     limit=>array(0,1) 表示从0开始，取一条记录。
     * @return array|bool
     */
    public function zRevRangeByScore($key, $start = 0, $end = 0, $option = [])
    {
        return $this->redis->zRevRangeByScore($key, $start, $end, $option);
    }

    /**
     * zCount 返回order值在start end之间的数量
     * @param $key
     * @param $start
     * @param $end
     * @return mixed
     */
    public function zCount($key, $start, $end)
    {
        return $this->redis->zCount($key, $start, $end);
    }

    /**
     * zScore 返回值为value的order值
     * @param $key
     * @param $value
     * @return mixed
     */
    public function zScore($key, $value)
    {
        return $this->redis->zScore($key, $value);
    }

    /**
     * zRank 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     * @param $key
     * @param $value
     * @return mixed
     */
    public function zRank($key, $value)
    {
        return $this->redis->zRank($key, $value);
    }

    /**
     * zRevRank 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     * @param $key
     * @param $value
     * @return mixed
     */
    public function zRevRank($key, $value)
    {
        return $this->redis->zRevRank($key, $value);
    }

    /**
     * zRemRangeByScore 删除集合中，score值在start end之间的元素　包括start end
     * min和max可以是-inf和+inf　表示最大值，最小值
     * @param $key
     * @param $start
     * @param $end
     * @return mixed 删除成员的数量
     */
    public function zRemRangeByScore($key, $start, $end)
    {
        return $this->redis->zRemRangeByScore($key, $start, $end);
    }

    /**
     * zCard 返回集合元素个数
     * @param $key
     * @return mixed
     */
    public function zCard($key)
    {
        return $this->redis->zCard($key);
    }

//endregion

//region 队列操作命令->list
    /*********************队列操作命令************************/

    /**
     * rPush 在队列尾部插入一个元素
     * @param $key
     * @param $value
     * @return mixed 返回队列长度
     */
    public function rPush($key, $value)
    {
        return $this->redis->rPush($key, $value);
    }

    /**
     * rPushx 在队列尾部插入一个元素 如果key不存在，什么也不做
     * @param $key
     * @param $value
     * @return mixed 返回队列长度
     */
    public function rPushx($key, $value)
    {
        return $this->redis->rPushx($key, $value);
    }

    /**
     * lPush 在队列头部插入一个元素
     * @param $key
     * @param $value
     * @return mixed 返回队列长度
     */
    public function lPush($key, $value)
    {
        return $this->redis->lPush($key, $value);
    }

    /**
     * lPushx 在队列头插入一个元素 如果key不存在，什么也不做
     * @param $key
     * @param $value
     * @return mixed 返回队列长度
     */
    public function lPushx($key, $value)
    {
        return $this->redis->lPushx($key, $value);
    }

    /**
     * lLen 返回队列长度
     * @param $key
     * @return mixed
     */
    public function lLen($key)
    {
        return $this->redis->lLen($key);
    }

    /**
     * lRange 返回队列指定区间的元素
     * @param $key
     * @param $start
     * @param $end
     * @return mixed
     */
    public function lRange($key, $start, $end)
    {
        return $this->redis->lrange($key, $start, $end);
    }

    /**
     * lIndex 返回队列中指定索引的元素
     * Created by PhpStorm.
     * User: w
     * Date: 2018-12-09
     * Time: 12:13
     * @param $key
     * @param $index
     * @return mixed
     */
    public function lIndex($key, $index)
    {
        return $this->redis->lIndex($key, $index);
    }

    /**
     * lSet 设定队列中指定index的值。
     * @param $key
     * @param $index
     * @param $value
     * @return mixed
     */
    public function lSet($key, $index, $value)
    {
        return $this->redis->lSet($key, $index, $value);
    }

    /**
     * lRem 删除值为value的count个元素
     * PHP-REDIS扩展的数据顺序与命令的顺序不太一样，不知道是不是bug
     * count>0 从尾部开始
     *  >0　从头部开始
     *  =0　删除全部
     * @param $key
     * @param $count
     * @param $value
     * @return mixed
     */
    public function lRem($key, $count, $value)
    {
        return $this->redis->lRem($key, $value, $count);
    }

    /**
     * lPop 删除并返回队列中的头元素
     * Created by PhpStorm.
     * User: w
     * Date: 2018-12-09
     * Time: 12:13
     * @param $key
     * @return mixed
     */
    public function lPop($key)
    {
        return $this->redis->lPop($key);
    }

    /**
     * rPop 删除并返回队列中的尾元素
     * @param $key
     * @return mixed
     */
    public function rPop($key)
    {
        return $this->redis->rPop($key);
    }
//endregion

//region redis字符串操作命令->string
    /*************redis字符串操作命令*****************/

    /**
     * set 设置一个key
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value)
    {
        return $this->redis->set($key, $value);
    }

    /**
     * get 得到一个key
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * setex 设置一个有过期时间的key
     * @param $key
     * @param $expire
     * @param $value
     * @return mixed
     */
    public function setex($key, $expire, $value)
    {
        return $this->redis->setex($key, $expire, $value);
    }

    /**
     * setnx 设置一个key,如果key存在,不做任何操作.
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setnx($key, $value)
    {
        return $this->redis->setnx($key, $value);
    }

    /**
     * mset 批量设置key
     * @param $arr
     * @return mixed
     */
    public function mset($arr)
    {
        return $this->redis->mset($arr);
    }
//endregion

//region 无序集合操作命令->set
    /*************redis　无序集合操作命令*****************/

    /**
     * sMembers 返回集合中所有元素
     * @param $key
     * @return mixed
     */
    public function sMembers($key)
    {
        return $this->redis->sMembers($key);
    }

    /**
     * sDiff 求2个集合的差集
     * @param $key1
     * @param $key2
     * @return mixed
     */
    public function sDiff($key1, $key2)
    {
        return $this->redis->sDiff($key1, $key2);
    }

    /**
     * sAdd 添加集合。由于版本问题，扩展不支持批量添加。这里做了封装
     * @param $key
     * @param string|array $value
     */
    public function sAdd($key, $value)
    {
        if (!is_array($value))
            $arr = array($value);
        else
            $arr = $value;

        foreach ($arr as $row)
            $this->redis->sAdd($key, $row);
    }

    /**
     * scard 返回无序集合的元素个数
     * Created by PhpStorm.
     * @param $key
     * @return mixed
     */
    public function scard($key)
    {
        return $this->redis->scard($key);
    }

    /**
     * srem 从集合中删除一个元素
     * @param $key
     * @param $value
     * @return mixed
     */
    public function srem($key, $value)
    {
        return $this->redis->srem($key, $value);
    }

    //endregion

//region redis管理操作命令
    /*************redis管理操作命令*****************/

    /**
     * 选择数据库
     * @param int $dbId 数据库ID号
     * @return bool
     */
    public function select($dbId)
    {
        $this->dbId = $dbId;
        return $this->redis->select($dbId);
    }

    /**
     * 清空当前数据库
     * @return bool
     */
    public function flushDB()
    {
        return $this->redis->flushDB();
    }

    /**
     * info 返回当前库状态
     * @return string
     */
    public function info()
    {
        return $this->redis->info();
    }

    /**
     * 同步保存数据到磁盘
     */
    public function save()
    {
        return $this->redis->save();
    }

    /**
     * 异步保存数据到磁盘
     */
    public function bgSave()
    {
        return $this->redis->bgSave();
    }

    /**
     * 返回最后保存到磁盘的时间
     */
    public function lastSave()
    {
        return $this->redis->lastSave();
    }

    /**
     * 返回key,支持*多个字符，?一个字符
     * 只有*　表示全部
     * @param string $key
     * @return array
     */
    public function keys($key)
    {
        return $this->redis->keys($key);
    }

    /**
     * del 删除指定key
     * @param $key
     * @return mixed
     */
    public function del($key)
    {
        return $this->redis->del($key);
    }

    /**
     * exists 判断一个key值是不是存在
     * Created by PhpStorm.
     * User: w
     * Date: 2018-12-09
     * Time: 12:07
     * @param $key
     * @return mixed
     */
    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    /**
     * expire 为一个key设定过期时间 单位为秒
     * @param $key
     * @param $expire
     * @return mixed
     */
    public function expire($key, $expire)
    {
        return $this->redis->expire($key, $expire);
    }

    /**
     * ttl 返回一个key还有多久过期，单位秒
     * @param $key
     * @return mixed
     */
    public function ttl($key)
    {
        return $this->redis->ttl($key);
    }

    /**
     * expireAt 设定一个key什么时候过期，time为一个时间戳
     * Created by PhpStorm.
     * User: w
     * Date: 2018-12-09
     * Time: 12:06
     * @param $key
     * @param $time
     * @return mixed
     */
    public function expireAt($key, $time)
    {
        return $this->redis->expireAt($key, $time);
    }

    /**
     * 关闭服务器链接
     */
    public function close()
    {
        return $this->redis->close();
    }

//    /**
//     * 关闭所有连接
//     */
//    public static function closeAll()
//    {
//        foreach (static::$_instance as $o) {
//            if ($o instanceof self)
//                $o->close();
//        }
//    }

    /** 这里不关闭连接，因为session写入会在所有对象销毁之后。
     * public function __destruct()
     * {
     * return $this->redis->close();
     * }
     **/
    /**
     * 返回当前数据库key数量
     */
    public function dbSize()
    {
        return $this->redis->dbSize();
    }

    /**
     * 返回一个随机key
     */
    public function randomKey()
    {
        return $this->redis->randomKey();
    }

    /**
     * 得到当前数据库ID
     * @return int
     */
    public function getDbId(): int
    {
        return $this->dbId;
    }

    /**
     * 返回当前密码
     */
    public function getAuth()
    {
        return $this->auth;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getConnInfo()
    {
        return [
            'host' => $this->host,
            'port' => $this->port,
            'auth' => $this->auth
        ];
    }

    //endregion

//region 事务的相关方法

    /*********************事务的相关方法************************/

    /**
     * watch 监控key,就是一个或多个key添加一个乐观锁
     * 在此期间如果key的值如果发生的改变，刚不能为key设定值
     * 可以重新取得Key的值。
     * @param $key
     * @return void
     */
    public function watch($key)
    {
        $this->redis->watch($key);
    }

    /**
     * 取消当前链接对所有key的watch
     *  EXEC 命令或 DISCARD 命令先被执行了的话，那么就不需要再执行 UNWATCH 了
     */
    public function unwatch()
    {
        $this->redis->unwatch();
    }

    /**
     * multi 开启一个事务
     * 事务的调用有两种模式Redis::MULTI和Redis::PIPELINE，
     * 默认是Redis::MULTI 模式，
     * Redis::PIPELINE管道模式速度更快，但没有任何保证原子性有可能造成数据的丢失
     * @param int $type
     * @return mixed
     */
    public function multi(int $type = Redis::MULTI)
    {
        return $this->redis->multi($type);
    }

    /**
     * 执行一个事务
     * 收到 EXEC 命令后进入事务执行，事务中任意命令执行失败，其余的命令依然被执行
     */
    public function exec()
    {
        return $this->redis->exec();
    }

    /**
     * 回滚一个事务
     */
    public function discard()
    {
        $this->redis->discard();
    }

    /**
     * 测试当前链接是不是已经失效
     * 没有失效返回+PONG
     * 失效返回false
     */
    public function ping()
    {
        return $this->redis->ping(null);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/6/2 11:36
     * @Description: 验证密码
     * @param $password
     * @return bool
     */
    public function auth($password): bool
    {
        return $this->redis->auth($password);
    }

    //endregion

//region 自定义的方法,用于简化操作*

    /*********************自定义的方法,用于简化操作************************/

    /**
     * hashAll 得到一组的ID号
     * @param $prefix
     * @param $ids
     * @return array|bool
     */
    public function hashAll($prefix, $ids)
    {
        if ($ids == false)
            return false;

        if (is_string($ids))
            $ids = explode(',', $ids);

        $arr = [];
        foreach ($ids as $id) {
            $key = $prefix . '.' . $id;
            $res = $this->hGetAll($key);
            if ($res != false)
                $arr[] = $res;
        }

        return $arr;
    }

    /**
     * pushMessage 生成一条消息，放在redis数据库中。
     * @param $lkey
     * @param string|array $msg
     * @return string
     */
    public function pushMessage($lkey, $msg)
    {
        if (is_array($msg))
            $msg = json_encode($msg);

        $key = md5($msg);

        //如果消息已经存在，删除旧消息，已当前消息为准
        //echo $n=$this->lRem($lkey, 0, $key)."\n";
        //重新设置新消息
        $this->lPush($lkey, $key);
        $this->setex($key, 3600, $msg);
        return $key;
    }

    /**
     * delKeys 得到条批量删除key的命令
     * @param $keys
     * @param $dbId
     * @return string
     */
    public function delKeys($keys, $dbId)
    {
        $redisInfo = $this->getConnInfo();
        $cmdArr    = [
            'redis-cli',
            '-a',
            $redisInfo['auth'],
            '-h',
            $redisInfo['host'],
            '-p',
            $redisInfo['port'],
            '-n',
            $dbId,
        ];
        $redisStr  = implode(' ', $cmdArr);
        $cmd       = "{$redisStr} KEYS \"{$keys}\" | xargs {$redisStr} del";
        return $cmd;
    }
    //endregion
}

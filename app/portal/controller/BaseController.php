<?php
/*
 * @Author: lpc
 * @DateTime: 2020/11/16 11:04
 * @Description: 控制器基类
 *
 * @return

 */


namespace app\portal\controller;

use think\App;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\facade\View;
use think\Validate;

//lpc 加上这个可屏蔽php的字段不存在警告提醒，然而我没有采用
//改用在需要字段前加@来抑制变量，同时保留了PHP提醒机制
//error_reporting(E_ALL & ~E_NOTICE);
class BaseController
{
    protected $limit = 10;

    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 15:03
     * @Description: 处理分页页码数据
     * @param  $pageNo
     * @param  $pageSize
     * @return array
     */
    public function getPageData($pageNo, $pageSize): array
    {
        $pageSize = !empty($pageSize) ? $pageSize : $this->limit;
        $pageNo   = !empty($pageNo) ? $pageNo : 1;
//        $offset   = ($pageNo - 1) * $pageSize;
        return ['page' => $pageNo, 'list_rows' => $pageSize];
    }

    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;


    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        //$this->request = $this->app->request;
        $this->request = app('request');

        //var_dump($this->response);
        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
    }

    /**
     * 验证数据
     * @access protected
     * @param array $data 数据
     * @param string|array $validate 验证器名或者验证规则数组
     * @param array $message 提示信息
     * @param bool $batch 是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

    protected function assign(...$arr)
    {
        View::assign(...$arr);
    }


    protected function redirect(...$args)
    {
        throw new HttpResponseException(redirect(...$args));
    }


}
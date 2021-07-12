ThinkPHP 6.0
===============

> 运行环境要求PHP7.1+，兼容PHP8.0。

[官方应用服务市场](https://market.topthink.com) | [`ThinkAPI`——官方统一API服务](https://docs.topthink.com/think-api)

ThinkPHPV6.0版本由[亿速云](https://www.yisu.com/)独家赞助发布

## 后台管理页面
~~~
http://121.36.161.35:9528/dashboard
~~~

## PHP代码部署安装

~~~
1、git拉取代码后可更新composer执行：composer update

2、创建.env文件，复制.example.env内容

3、开始使用啦
~~~

更新tp框架

~~~
composer update topthink/framework
~~~

## VUE代码部署安装

~~~
vue目录在views目录下
1、如果npm i报错，可以先跑下以下命令
npm install --save-dev sass-loader
npm config set sass_binary_site=https://npm.taobao.org/mirrors/node-sass
2、调试模式：npm run dev
       打包：npm run build:prod
~~~

## 接口文档

~~~
基于TP6的一款前后端分离管理系统
https://docs.apipost.cn/preview/787d44633670a7e4/484894853cd84ede#001
~~~

## 配置项

~~~
很多配置项写在了.env文件里，参考文件.example.env部署
~~~

## 开发者个人思路

~~~
1、平台在调用service或者utils等只需要单例模式时；
    提供了Kernel::single(SmsService::class);写法；
    (ps:本来打算自己写，后发现tp自带有,直接封装使用了)

2、contro层在获取请求参数时进行了封装:
    使用中间件RequestParam来获取请求参数；
    继承了baseController的可直接$data = $this->dataParams;
 
3、在支付、短信、队列等这些扩展使用了工厂模式，在utils下都加了对应的工具类;
   然后在service层去调用对应的工具类;
   这样的目的:
        一是为了保证扩展的独立性;
        二是可以在工具类里做一些个性化操作;
~~~

## 存在的问题

~~~
1、日志问题:
    拿支付扩展来说，我在基类里写了$this->log()用来给扩展记录支付日志。
    但是造成了我每次写一个扩展就要手动在对应方法里调用日志方法.
    比如aliAppPay里调起pay方法我要记录一遍，写wxAppPay我又要写一遍，再添加一个忘记写了就记不下来了。
    如果在调用支付的util类里加，不过是换汤不换药，我payment还是要传处理数据回来我才能记录。
    相应的问题还存在于队列里，记录下来等待有缘人告知。

2、权限管理实现跟前端问题:
    简单的来说就是权限管理只适合本套前端代码。
    拿出接口去对接其他前端可能很难一下接上。
    又草率了...不过问题不大。 
~~~

## 系统功能

~~~
1、后台RBAC权限管理：【已完成】
    本系统角色权限管理分开菜单管理跟功能权限
    菜单权限负责前台侧边栏展示，功能权限负责角色是否能操作
    菜单权限实现：
        数据库存放了menu菜单表
        然后通过角色关联对应菜单来实现前端菜单栏权限。
    功能权限实现：
        admin应用的路由的append加了个is_permission字段;
        为true时表示必须走权限，不写或false表示不走权限。
        功能权限检查在app\admin\middleware\CheckToken里
       
2、第三方支付扩展：【已完成】
    提供服务类：app\portal\service\PaymentService
    配置文件：config/pay.php
    已配置【app端支付宝】
        
3、文件上传扩展：【已完成】
    提供服务类：app\common\service\UploadService
    配置文件：config/upload.php
    已配置【本地、七牛云】
        
4、消息队列扩展：【草率了...】
    提供服务类：app\common\service\TaskService
    暂时实现了think_queue跟rabbitMq的延迟、正常发布队列两种.
    配置文件：config/sys_task.php
    
    开启消费者：php think task_consumer
            PS：运行前php.ini将system()从禁用里去掉；
            
    设计思路：在配置文件下配置好系统要使用的队列
              然后就去开启消费者监听命令，php think task_consumer
              PS:在开启task_consumer时，tp自带think_queue支持同时开启多个消费者;
              其他自定义的mq开启多个消费者因为会阻塞,不能循环开启消费者;
              我在task_consumer里循环执行:php think enable_queue 队列名来批量开启消费者；
              
    使用方法代码：
     #--------队列使用案例 start---------
        $data = new QueueParamsDto();
        $data->setData(['ts' => time(), 'bizId' => uniqid(), 'a' => 1]);
        $data->setTaskClass(TestTask::class);
        //lpc route主要在rabbit里用，queueName是tp自带的用，都有默认值
        //$data->setRoutes(['cancel_order','notify']);
        //$data->setQueueName('default_queue');

        $res = Kernel::single(TaskService::class)->publish($data);//即时队列
        $res = Kernel::single(TaskService::class)->publish($data,10);//延时队列,10秒后执行
    #--------队列使用案例 end---------
    
              
        
5、短信扩展：【已完成】
    提供服务类：app\common\service\SmsService
    配置文件：config/sms.php
    支持了可选短信发送类型【code码、自定义】
    支持了短信直接发送、队列发送、队列延时发送
    平台可查看短信发送状态，支持重发操作

6、请求缓存：【TP自带直接使用】

7、导入导出扩展：【已完成】
    提供服务类：app\common\service\ExcelService
    配置文件：config/excel.php
    
    导出支持返回下载链接、直接输出文件流
    导入支持url、本地文件读取

..........
~~~


ThinkPHP 6.0
===============

> 运行环境要求PHP7.1+，兼容PHP8.0。

[官方应用服务市场](https://market.topthink.com) | [`ThinkAPI`——官方统一API服务](https://docs.topthink.com/think-api)

ThinkPHPV6.0版本由[亿速云](https://www.yisu.com/)独家赞助发布。

## 主要新特性

* 采用`PHP7`强类型（严格模式）
* 支持更多的`PSR`规范
* 原生多应用支持
* 更强大和易用的查询
* 全新的事件系统
* 模型事件和数据库事件统一纳入事件系统
* 模板引擎分离出核心
* 内部功能中间件化
* SESSION/Cookie机制改进
* 对Swoole以及协程支持改进
* 对IDE更加友好
* 统一和精简大量用法

## 安装

~~~
composer create-project topthink/think tp 6.0.*
~~~

如果需要更新框架使用

~~~
composer update topthink/framework
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

## 开发中~

~~~
目前开发完成：
    平台端权限、登录
    第三方支付扩展；已配置【app端支付宝】
    文件上传扩展；已配置【本地、七牛云】
~~~

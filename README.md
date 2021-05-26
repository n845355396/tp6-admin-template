ThinkPHP 6.0
===============

> 运行环境要求PHP7.1+，兼容PHP8.0。

[官方应用服务市场](https://market.topthink.com) | [`ThinkAPI`——官方统一API服务](https://docs.topthink.com/think-api)

ThinkPHPV6.0版本由[亿速云](https://www.yisu.com/)独家赞助发布

## PHP代码部署安装

~~~
1、git拉取代码后执行：composer update

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

## 系统功能

~~~
1、RBAC权限管理：【已完成】
       本系统角色权限管理分开菜单管理跟功能权限
       菜单权限负责前台侧边栏展示，功能权限负责角色是否能操作
       
2、第三方支付扩展：【已完成】
        已配置【app端支付宝】
        
3、文件上传扩展：【已完成】
        已配置【本地、七牛云】
        
4、消息队列扩展：【懵了...】
        暂时实现了think_queue跟rabbitMq的延迟、正常发布队列两种.
        感觉写的不太行，先搁着吧
        
5、短信扩展：【开发中】

..........
~~~

# phpJourney
#### 1、概述
本项目使用 PHP 框架 Laravel 5.5 进行开发。系统后台使用了Vuejs + Element-UI实现完全的前后端分离。并且使用高性能的swoole作为http服务器。
*    项目地址：[http://phpjourney.xin](http://phpjourney.xin)(正在备案，暂时可通过[http://47.94.11.137](http://47.94.11.137)访问)
*    GitHub地址：[https://github.com/Johnson19900110/phpJourney](https://github.com/Johnson19900110/phpJourney)
#### 2、功能特性
*   分类管理
*   标签管理
*   文章管理
*   评论管理
*   支持markdown语法
#### 3、部署／安装
需要在系统上安装了基本的PHP运行环境、PHP包管理工具composer、Nodejs进行前端资源打包npm。

基础安装

**克隆源代码**
> `git clone https://github.com/Johnson19900110/phpJourney`

**安装php拓展包依赖**
> `composer install`

**生成配置文件**
> `cp .env.example .env`

然后根据自己的配置信息去配置文件

**生成key**
> `php artisan key:generate`

**数据库迁移**
> `php artisan migrate`

**数据库填充**
> `php artisan db:seed`

暂时只添加了一个后台的管理用户，想要看到完全的效果可以去后台添加一些测试数据。

以上是使用nginx作为http服务器访问，但本项目支持swoole作为http服务器。所以需要在一台已经安装swoole拓展的linux或mac上运行。

配置文件在`config/swoole_http.php`中,这里的配置将会配写到swoole_http_server中，具体见[https://wiki.swoole.com/wiki/page/274.html](https://wiki.swoole.com/wiki/page/274.html)
```php
'options' => [
    'pid_file' => env('SWOOLE_HTTP_PID_FILE', base_path('storage/logs/swoole_http.pid')),
    'log_file' => env('SWOOLE_HTTP_LOG_FILE', base_path('storage/logs/swoole_http.log')),
    'daemonize' => env('SWOOLE_HTTP_DAEMONIZE', 1), // 是否后台启动进程
],
```

启动`swoole_http_server`
> `php artisan swoole:http start`

停止`swoole_http_server`
> `php artisan swoole:http stop`

重启`swoole_http_server`
> `php artisan swoole:http restart`

当修改`swoole_http`config文件是，可以使用`php artisan swoole:http reload`重新加载配置文件

swoole官网说：swoole_http_server对Http协议的支持并不完整，建议仅作为应用服务器。并且在前端增加Nginx作为代理
所以我们还需要配置nginx文件。
```nginx

server {
    listen 80;
    server_name your.domain.com;
    root /path/to/laravel/public;
    index index.php;

    location = /index.php {
        # Ensure that there is no such file named "not_exists"
        # in your "public" directory.
        try_files /not_exists @swoole;
    }

    location / {
        try_files $uri $uri/ @swoole;
    }

    location @swoole {
        set $suffix "";

        if ($uri = /index.php) {
            set $suffix "/";
        }

        proxy_set_header Host $host;
        proxy_set_header SERVER_PORT $server_port;
        proxy_set_header REMOTE_ADDR $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;

        # IF https
        # proxy_set_header HTTPS "on";
        
        # The Hostname and Port of swoole_http_server
        proxy_pass http://127.0.0.1:1215$suffix;
    }
}
```

##### 前后台入口
* 前台入口：`http://example.com/`
* 后台入口：`http://example.com/back`

默认用户名为：phpjourney@johnson.com ，密码为：123456789

默认前端编译的js文件和css已经编译好了，如果你不需要修改样式，那到此就结束了，否则你就要安装nodejs
和其前端管理工具npm，然后运行`npm install`安装前端包(windows上面可能会遇到问题,但mac和linux都不会出任何错)。
包安装完成后运行`npm run watch`,这样就可以及时监控你修改的js和css，如果一次就调整完了，可以使用`npm run dev`。

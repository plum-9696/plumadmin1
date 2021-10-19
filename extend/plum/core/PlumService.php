<?php

namespace plum\core;

use plum\core\exception\ExceptionHandle;
use think\exception\Handle;
use think\Service;

class PlumService extends Service
{
    public function register()
    {
        // 服务注册
        //注册异常
        $this->registerException();
        //注册query
        $this->registerQuery();
    }

    public function boot()
    {
        // 服务启动

        //跨域配置
        $this->allowCrossDomain();

    }

    /**
     * 跨域配置
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年06月08日 22:38
     */
    public function allowCrossDomain()
    {
        header('Access-Control-Allow-Credentials:true');
        header('Access-Control-Max-Age:1800');
        header('Access-Control-Allow-Methods:GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers:Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With, access-token');
        header("Access-Control-Allow-Origin:*");
    }

    /**
     * 注册异常
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年06月08日 12:04
     */
    public function registerException()
    {
        $this->app->bind(Handle::class, ExceptionHandle::class);
    }

    /**
     * 注册query
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年06月09日 18:01
     */
    public function registerQuery()
    {
        $connections = $this->app->config->get('database.connections');
        // 支持多数据库配置注入 Query
        foreach ($connections as &$connection) {
            $connection['query'] = PlumQuery::class;
        }
        $this->app->config->set([
            'connections' => $connections
        ], 'database');
    }
}

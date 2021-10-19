<?php

return [
    //超级管理员,不用走权限
    'super_id'  => 1,
    'token'     => [
        'cache_prefix'   => 'token', //缓存的前缀
        'expire'         => 7200,    //token有效期(2小时)
        'refresh_expire' => 604800,  //refresh_token有效期(7天)
    ],
    //需要配置下系统设置那些缓存标签.方便清理
    'cache_tag' => [
        ['name' => '系统设置', 'key' => 'setting'],
    ],
    'timer'     => [],
    'queue'     => []

];

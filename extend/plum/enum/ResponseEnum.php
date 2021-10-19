<?php

namespace plum\enum;

class ResponseEnum
{
    public const SUCCESS = 10000; // 成功
    public const USER_FORBIDDEN = 10001; // 账号被禁止
    public const FAILED = 10400; // 失败
    public const LOST_LOGIN = 10401; //  登录失效
    public const PERMISSION_FORBIDDEN = 10403; // 权限禁止
    public const ROUTE_FAIL = 10404; // 找不到
    public const SERVER_FAIL = 10500; // 服务器异常
}

<?php

namespace plum\core\exception;

use plum\enum\ResponseEnum;

class AuthException extends Exception
{
    public $code = ResponseEnum::LOST_LOGIN;
    public $message = '未认证';
}

<?php

namespace plum\core\exception;


use plum\enum\ResponseEnum;

class ForbiddenException extends Exception
{
    public $code = ResponseEnum::USER_FORBIDDEN;
    public $message = '账号已禁用,请联系管理员';
}

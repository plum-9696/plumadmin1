<?php

namespace plum\core\exception;

use plum\enum\ResponseEnum;

class PermissionException extends Exception
{
    public $code = ResponseEnum::PERMISSION_FORBIDDEN;
    public $message = '抱歉,你无权访问';
}

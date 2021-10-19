<?php

namespace plum\core\exception;

use plum\enum\ResponseEnum;

class FailException extends Exception
{
    public $code = ResponseEnum::FAILED;
    public $message = 'ERROR';
}

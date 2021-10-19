<?php

namespace plum\core\exception;

use think\Exception as ThinkException;
use Throwable;

abstract class Exception extends ThinkException
{
    protected $message;
    protected $code;

    public function __construct($message = null, $code = null, Throwable $previous = null)
    {
        $this->message = $message ?: $this->message;
        $this->code = $code ?: $this->code;
        parent::__construct($this->message, $this->code, $previous);
    }
}

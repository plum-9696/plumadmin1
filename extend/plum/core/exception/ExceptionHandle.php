<?php

namespace plum\core\exception;

use plum\core\Trace;
use plum\enum\ResponseEnum;
use plum\lib\Utils;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\facade\Db;
use think\Response;
use Throwable;

class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
        Exception::class
    ];


    /**
     * 记录异常信息（包括日志或者其它方式记录）
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年09月30日 20:16
     */
    public function report(Throwable $exception): void
    {
        //使用数据库的方式记录日志
        if (!$this->isIgnoreReport($exception)) {
            Utils::recordError($exception);
        }
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * 渲染异常
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年09月30日 20:32
     */
    public function render($request, Throwable $e): Response
    {
        $debug = app()->isDebug();
        //默认忽略异常配置
        $data = [
            'code'    => $e->getCode(),
            'message' => $e->getMessage(),
        ];
        //路由错误
        if ($e instanceof HttpException) {
            $data['code'] = ResponseEnum::ROUTE_FAIL;
            $data['message'] = '页面不存在';
        }
        if (!$this->isIgnoreReport($e) && !$debug) {
            //内部异常,且线上模式
            $data['code'] = ResponseEnum::SERVER_FAIL;
            $data['message'] = 'SERVER FAIL';
        }
        //调试信息
        if ($debug) {
            $data['trace'] = (new Trace())->traceDebug();
        }
        return json($data);
    }
}

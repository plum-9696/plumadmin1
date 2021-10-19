<?php

namespace app\admin\controller;

use app\admin\model\AdminModel;
use app\admin\model\RuleModel;
use app\BaseController;
use app\common\model\OperationLogModel;
use plum\core\exception\PermissionException;
use plum\core\Token;
use plum\core\traits\ResponseTrait;
use plum\lib\Arr;
use plum\lib\Utils;
use think\Exception;
use think\exception\RouteNotFoundException;
use think\facade\Event;

class Controller extends BaseController
{
    use ResponseTrait;

    protected $admin;                   //用户登录信息
    protected $whiteList = [];          //白名单,不需要登录
    protected $forceRequestMethod = []; //强制请求方法
    private $route;                     //当前的路由信息
    private $action;                    //当前的方法
    protected $opLog = [];

    protected function initialize()
    {
        //获取当前的路由信息
        $this->routeInfo();
        //校验登录
        $this->checkLogin();
        //校验强制请求方法
        $this->forceRequestMethod();
        //校验当前的权限
        $this->checkPermission();
        //记录日志
        $this->recordLog();
    }

    /**
     * 校验当前的权限
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 17:12
     */
    public function checkPermission()
    {
        //超级管理员不需要校验
        if (!in_array($this->action, $this->whiteList) || ($this->admin && config('plum.super_id') !== $this->admin->id)) {
            //获取当前路由方法
            $rule = AdminModel::getRule($this->admin->id)->toArray();
            $perms = array_values(array_filter($rule, function ($item) {
                return $item['type'] === RuleModel::TYPE_PERM;
            }));
            $perms = array_column($perms,'perms');
            $perms = array_values(array_unique($perms));
            if(!Arr::inArray($this->route,$perms)){
                throw new PermissionException();
            }
        }
    }

    /**
     * 强制校验请求方法
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 17:02
     */
    private function forceRequestMethod()
    {
        if (isset($this->forceRequestMethod[$this->action])) {
            $rule = $this->forceRequestMethod[$this->action];
            $method = strtolower($this->request->method());
            if (is_string($rule) && $rule !== $method) {
                throw new RouteNotFoundException();
            }
            if (is_array($rule) && !in_array($method, $rule)) {
                throw new RouteNotFoundException();
            }
        }
    }

    /**
     * 校验登录
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 16:51
     */
    private function checkLogin()
    {
        //白名单之内的不需要登录
        if (!in_array($this->action, $this->whiteList)) {
            //调试模式,可以直接使用uuid
            if ($this->app->isDebug() && $this->request->param('uuid')) {
                $id = $this->request->param('uuid');
            } else {
                $id = Token::auth('admin');
            }
            $this->admin = AdminModel::getUserinfo($id);
        }
    }

    /**
     * 当前的路由信息
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 16:50
     */
    private function routeInfo()
    {
        $this->action = $this->request->action();
        $this->route = $this->request->controller() . '@' . $this->action;
    }

    /**
     * 记录log
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 17:13
     */
    private function recordLog()
    {
        Event::listen('think\event\LogWrite', function ($event) {
            try {
                $message = '';
                if (isset($this->opLog[$this->action])) {
                    $message = $this->opLog[$this->action];
                }
                if (isset($event->log['op']) && count($event->log['op']) > 0) {
                    //记录日志
                    $message = implode(';', $event->log['op']);
                }
                if (!empty($message)) {
                    //写入操作日志
                    OperationLogModel::create([
                        'message'      => $message,
                        'route'        => $this->route,
                        'ip'           => $this->request->ip(),
                        'operator_id' => $this->admin ? $this->admin->id : 0,
                        'param'        => json_encode($this->request->param(), JSON_UNESCAPED_UNICODE)
                    ]);
                }
            } catch (Exception $e) {
                Utils::recordError($e,'记录操作日志失败');
            }

        });
    }
}

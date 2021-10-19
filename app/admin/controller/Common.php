<?php

namespace app\admin\controller;

use app\common\model\SettingModel;
use plum\lib\Utils;
use think\facade\Cache;

class Common extends Controller
{
    protected $whiteList = ['website'];

    /**
     * 所有权限
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月06日 19:49
     */
    public function perms()
    {
        //获取当前的所有目录
        $files = Utils::getAllFile($this->app->getAppPath() . 'controller', 'php');
        //获取所有的权限
        $data = array_map(function ($item) {
            //获取类名,去除根目录部分
            $class = str_replace($this->app->getRootPath(), '', $item);
            //去除.php
            $class = strstr($class, '.php', true);
            //转化类名
            $class = implode('\\', explode(DIRECTORY_SEPARATOR, $class));
            //获取控制器名,多级用点分隔
            $controller = str_replace($this->app->getAppPath() . 'controller' . DIRECTORY_SEPARATOR, '', $item);
            //去除.php
            $controller = strstr($controller, '.php', true);
            //转化控制器
            $controller = strtolower(implode('.', explode(DIRECTORY_SEPARATOR, $controller)));
            //反射方法
            $data = new \ReflectionClass($class);
            $methods = $data->getMethods(\ReflectionMethod::IS_PUBLIC);
            //去除魔术方法
            $methods = array_values(array_filter($methods, function ($item) {
                return strpos($item->getName(), '__') !== 0;
            }));
            $methods = array_map(function ($item) {
                return [
                    'label' => $item->getName(),
                    'value' => $item->getName()
                ];
            }, $methods);
            return [
                'label'    => $controller,
                'value'    => $controller,
                'children' => $methods
            ];
        }, $files);
        return $this->renderSuccess($data);
    }


    /**
     * 清理缓存标签
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月13日 01:19
     */
    public function clearCache()
    {
        $tags = $this->request->param('tags/a');
        $configTags = array_column(config('plum.cache_tag'), 'key');
        Cache::tag(array_intersect($tags, $configTags))->clear();
        return $this->renderSuccess();
    }

    /**
     * 获取缓存标签
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月13日 01:19
     */
    public function cacheTag()
    {
        return $this->renderSuccess(config('plum.cache_tag'));
    }

    /**
     * 站点详情
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月13日 16:23
     */
    public function website()
    {
        return $this->renderSuccess(SettingModel::getItem('website'));
    }

}

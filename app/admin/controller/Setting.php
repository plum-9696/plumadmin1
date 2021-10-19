<?php

namespace app\admin\controller;

use app\common\model\SettingModel;

class Setting extends Controller
{
    /**
     * 获取
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月12日 20:42
     */
    public function get()
    {
        $key = $this->request->param('key');
        $data = SettingModel::getItem($key);
        return $this->renderSuccess($data);
    }

    /**
     * 设置
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月12日 20:42
     */
    public function set()
    {
        $key = $this->request->param('key');
        $value = $this->request->param('value/a');
        if(SettingModel::setItem($key,$value)===false){
            abort('保存失败');
        }
        return $this->renderSuccess();
    }
}

<?php

namespace app\admin\controller;


use app\admin\model\RuleModel;
use plum\lib\Arr;
use plum\lib\Utils;


class Rule extends Controller
{
    protected $whiteList = ['info'];
    /**
     * 树状
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 00:36
     */
    public function tree()
    {
        $list = RuleModel::plumOrder()
            ->plumSearch()
            ->select();
        //树状
        $list = Arr::tree($list);
        return $this->renderSuccess($list);
    }

    /**
     * 创建
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 00:38
     */
    public function create()
    {
        //验证
        $data = $this->request->param();
        $this->validate($data, [
            'name|名称' => 'require'
        ]);
        RuleModel::create($data);
        return $this->renderSuccess();
    }

    /**
     * 修改
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 13:04
     */
    public function update()
    {
        //验证
        $data = $this->request->param();
        $this->validate($data, [
            'name|名称' => 'require'
        ]);
        RuleModel::update($data);
        return $this->renderSuccess();
    }

    /**
     * 删除
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 13:04
     */
    public function delete()
    {
        $ids = $this->request->param('ids/a');
        $ids = array_merge(Utils::getChildrenIds(new RuleModel(),$ids),$ids);
        RuleModel::destroy($ids);
        return $this->renderSuccess();
    }

    /**
     * 详情
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 14:35
     */
    public function info()
    {
        $info = RuleModel::find($this->request->param('id'));
        if(!$info){
            abort('数据不存在');
        }
        return $this->renderSuccess($info);
    }

}

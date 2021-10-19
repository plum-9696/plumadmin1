<?php

namespace app\admin\controller;

use app\admin\model\AdminModel;
use app\Admin\model\DeptModel;
use plum\lib\Arr;
use plum\lib\Utils;
use think\Exception;

class Dept extends Controller
{
    /**
     * 列表
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 14:56
     */
    public function tree()
    {
        $list = DeptModel::plumSearch()
            ->order('sort asc')
            ->select();
        $list = Arr::tree($list);
        return $this->renderSuccess($list);
    }

    /**
     * 修改
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 14:56
     */
    public function update()
    {
        $data = $this->request->param();
        $this->validate($data, [
            'name|部门名称' => 'require'
        ]);
        DeptModel::update($data);
        return $this->renderSuccess();
    }

    /**
     * 更新
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月12日 17:55
     */
    public function sort()
    {
        $data = $this->request->param('data');
        (new DeptModel)->saveAll($data);
        return $this->renderSuccess();
    }

    /**
     * 创建
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 14:56
     */
    public function create()
    {
        $data = $this->request->param();
        $this->validate($data, [
            'name|部门名称' => 'require'
        ]);
        DeptModel::create($data);
        return $this->renderSuccess();
    }

    /**
     * 删除
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 14:56
     */
    public function delete()
    {
        $id = $this->request->param('id');
        try {
            //保留用户,存入上一级
            $ids = Utils::getChildrenIds(new DeptModel(),[$id]);
            array_push($ids,$id);
            $info = DeptModel::find($id);
            AdminModel::whereIn('dept_id', $ids)
                ->data(['dept_id' => $info['parent_id']])
                ->update();
            DeptModel::destroy($ids);
        } catch (Exception $e) {
            abort('删除失败');
        }
        return $this->renderSuccess();
    }

    /**
     * 详细
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 14:56
     */
    public function info()
    {
        $info = DeptModel::find($this->request->param('id'));
        if (!$info) {
            abort('数据不存在');
        }
        return $this->renderSuccess($info);
    }
}

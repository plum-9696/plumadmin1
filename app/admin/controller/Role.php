<?php

namespace app\admin\controller;

use app\admin\model\RoleModel;

class Role extends Controller
{
    /**
     * 分页
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 14:25
     */
    public function page()
    {
        $page = RoleModel::plumSearch()
            ->plumOrder()
            ->paginate();
        return $this->renderPage($page);
    }

    /**
     * 列表
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月06日 18:30
     */
    public function list()
    {
        $list = RoleModel::plumSearch()
            ->plumOrder()
            ->select();
        return $this->renderSuccess($list);
    }

    /**
     * 更新
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 14:29
     */
    public function update()
    {
        $data = $this->request->param();
        $this->validate($data, [
            'name|角色名称' => 'require'
        ]);
        RoleModel::edit($data);
        return $this->renderSuccess();
    }

    /**
     * 创建
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 14:29
     */
    public function create()
    {
        $data = $this->request->param();
        $this->validate($data, [
            'name|角色名称' => 'require'
        ]);
        RoleModel::add($data);
        return $this->renderSuccess();
    }

    /**
     * 删除
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 14:29
     */
    public function delete()
    {
        $ids = $this->request->param('ids/a');
        RoleModel::destroy($ids);
        return $this->renderSuccess();
    }

    public function info()
    {
        $info = RoleModel::find($this->request->param('id'));
        if(!$info){
            abort('数据不存在');
        }
        return $this->renderSuccess($info);
    }
}

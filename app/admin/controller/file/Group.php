<?php

namespace app\admin\controller\file;

use app\admin\controller\Controller;
use app\admin\model\file\GroupModel;
use app\admin\model\FileModel;
use plum\lib\Arr;
use plum\lib\Utils;

class Group extends Controller
{
    /**
     * 树状
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 14:03
     */
    public function tree()
    {
        $list = GroupModel::plumOrder()
            ->select();
        return $this->renderSuccess(Arr::tree($list));
    }

    /**
     * 创建
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 14:03
     */
    public function create()
    {
        $data = $this->request->param();
        $this->validate($data, [
            'name|分组名' => 'require'
        ]);
        GroupModel::create($data);
        return $this->renderSuccess();
    }

    /**
     * 更新
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 14:03
     */
    public function update()
    {
        $data = $this->request->param();
        $this->validate($data, [
            'name|分组名' => 'require'
        ]);
        GroupModel::update($data);
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
        (new GroupModel)->saveAll($data);
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
            $ids = Utils::getChildrenIds(new GroupModel(),[$id]);
            array_push($ids,$id);
            $info = GroupModel::find($id);
            FileModel::whereIn('group_id', $ids)
                ->data(['group_id' => $info['parent_id']])
                ->update();
            GroupModel::destroy($ids);
        } catch (Exception $e) {
            abort('删除失败');
        }
        return $this->renderSuccess();
    }
}

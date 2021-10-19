<?php

namespace app\admin\controller;

use app\admin\model\FileModel;
use app\common\model\SettingModel;
use think\Exception;
use think\facade\Filesystem;

class File extends Controller
{
    /**
     * 上传
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 21:46
     */
    public function upload()
    {
        $file = (new FileModel())->upload($this->request->param('group_id', 0));
        return $this->renderSuccess($file);
    }

    /**
     * 分页
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 13:43
     */
    public function page()
    {
        $model = new FileModel();
        return $this->renderPage($model->getPage());
    }

    /**
     * 根据ids获取列表
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 13:43
     */
    public function listByIds()
    {
        $model = new FileModel();
        return $this->renderSuccess($model->getListByIds($this->request->param('ids/a', [])));
    }

    /**
     * 删除
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 13:43
     */
    public function delete()
    {
        $model = new FileModel();
        $model->deleteItem($this->request->param('ids/a'));
        return $this->renderSuccess();
    }

    /**
     * 转移分组
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 13:43
     */
    public function moveGroup()
    {
        $groupId = $this->request->param('group_id');
        $ids = $this->request->param('ids/a', []);
        FileModel::update(['group_id' => $groupId], [['id', 'in', $ids]]);
        return $this->renderSuccess();
    }
}

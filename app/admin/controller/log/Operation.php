<?php

namespace app\admin\controller\log;

use app\admin\controller\Controller;
use app\admin\model\OperationLogModel;
use think\facade\Db;

class Operation extends Controller
{
    /**
     * 分页
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月13日 12:29
     */
    public function page()
    {
        $page = OperationLogModel::with('operator')
        ->plumOrder()
            ->plumSearch()
            ->paginate();
        return $this->renderPage($page);
    }

    /**
     * 删除
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月13日 12:29
     */
    public function delete()
    {
        $ids = $this->request->param('ids/a');
        OperationLogModel::destroy($ids);
        return $this->renderSuccess();
    }

    /**
     * 全部清空
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月13日 12:50
     */
    public function clear()
    {
        Db::table('operation_log')->delete(true);
        return $this->renderSuccess();
    }
}

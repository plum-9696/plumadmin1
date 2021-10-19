<?php

namespace app\admin\controller\log;

use app\admin\controller\Controller;
use app\common\model\DebugLogModel;
use think\facade\Db;

class Debug extends Controller
{
    /**
     * 分页
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月13日 12:29
     */
    public function page()
    {
        $page = DebugLogModel::plumOrder()
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
        DebugLogModel::destroy($ids);
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
        Db::table('debug_log')->delete(true);
        return $this->renderSuccess();
    }
}

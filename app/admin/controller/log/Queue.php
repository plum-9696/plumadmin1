<?php

namespace app\admin\controller\log;

use app\admin\controller\Controller;
use app\admin\service\QueueService;
use plum\lib\Log;

class Queue extends Controller
{
    /**
     * page
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月18日 13:24
     */
    public function page()
    {
        return $this->renderPage(QueueService::page());
    }

    /**
     * clear
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月18日 13:24
     */
    public function clear()
    {
        QueueService::clear(input('send'));
        return $this->renderSuccess();
    }
}

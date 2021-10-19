<?php

namespace app\common\model;

class DeptModel extends BaseModel
{
    protected $table = 'dept';
    protected $autoWriteTimestamp = 'timestamp';

    /**
     * 搜索器 - keyword
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 15:09
     */
    public function searchKeywordAttr($query,$value)
    {
        $query->whereLike('name',"%{$value}%");
    }
}

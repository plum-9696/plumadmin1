<?php

namespace app\common\model;

class OperationLogModel extends BaseModel
{
    protected $table = 'operation_log';

    /**
     * 搜索器 - time
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月13日 14:59
     */
    public function searchTimeAttr($query,$value)
    {
        //对于末尾,不全天数
        $value[1] .= ' 23:59:59';
        $query->whereTime('create_time','between',$value);
    }

    /**
     * 关联表 operator
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月14日 21:50
     */
    public function operator()
    {
        return $this->belongsTo(AdminModel::class,'operator_id','id')->bind(['operator'=>'nickname']);
    }
}

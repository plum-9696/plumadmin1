<?php

namespace app\common\model;

class RoleModel extends BaseModel
{
    protected $table = 'role';
    protected $autoWriteTimestamp = 'timestamp';

    /**
     * 关联模型 - 多对多 - dept
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 13:48
     */
    public function dept()
    {
        return $this->belongsToMany(DeptModel::class, RoleDeptModel::class);
    }

    /**
     * 关联模型 - 多模型 - rule
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 14:01
     */
    public function rule()
    {
        return $this->belongsToMany(RuleModel::class, RoleRuleModel::class);
    }

    /**
     * 搜索器 - keyword
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 14:23
     */
    public function searchKeywordAttr($query,$value)
    {
        $query->whereLike('name', "%{$value}%");
    }
}

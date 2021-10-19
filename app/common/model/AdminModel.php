<?php

namespace app\common\model;

use plum\lib\Utils;
use think\model\concern\SoftDelete;

class AdminModel extends BaseModel
{
    use SoftDelete;

    protected $table = 'admin';
    protected $autoWriteTimestamp = 'timestamp';
    const STATUS_DISABLE = 2;
    const STATUS_NORMAL = 1;

    /**
     * 关联 - role
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 15:34
     */
    public function role()
    {
        return $this->belongsToMany(RoleModel::class, AdminRoleModel::class, 'role_id', 'admin_id');
    }

    /**
     * 获取器 - role_id
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月14日 16:40
     */
    public function getRoleIdsAttr($value, $data)
    {
        return AdminRoleModel::where('admin_id', $data['id'])->column('role_id');
    }

    /**
     * 部门
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月14日 16:25
     */
    public function dept()
    {
        return $this->belongsTo(DeptModel::class, 'dept_id', 'id')->bind(['dept_name' => 'name']);
    }

    /**
     * 搜索器 - keyword
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 17:11
     */
    public function searchKeywordAttr($query, $value)
    {
        $query->whereLike('phone|username|nickname|email', "%{$value}%");
    }

    /**
     * 搜索器 - dept_id
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 17:18
     */
    public function searchDeptIdAttr($query, $value)
    {
        $value = [$value];
        $value = array_merge($value, Utils::getChildrenIds(new DeptModel(), $value));
        $query->whereIn('dept_id', $value);
    }
}

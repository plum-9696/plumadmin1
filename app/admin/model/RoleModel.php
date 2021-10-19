<?php

namespace app\admin\model;

use app\common\model\DeptModel;
use app\common\model\RoleModel as Model;
use app\common\model\RoleRuleModel;
use think\Exception;

class RoleModel extends Model
{
    /**
     * 新增
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 13:54
     */
    public static function add($data)
    {
        try {
            self::startTrans();
            $role = self::create($data);
            //关联权限
            $ruleIds = $data['rule_ids'] ?? [];
            $role->rule()->saveAll($ruleIds);
            //关联部门
            $deptIds = $data['dept_ids'] ?? [];
            $role->dept()->saveAll($deptIds);
            self::commit();
        } catch (Exception $e) {
            self::rollback();
            abort('保存失败');
        }
    }

    /**
     * 编辑
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 13:57
     */
    public static function edit($data)
    {
        try {
            self::startTrans();
            $role = self::findOrFail($data['id']);
            $role->save($data);
            //关联权限
            $ruleIds = $data['rule_ids'] ?? [];
            RoleRuleModel::where('role_id', $data['id'])->delete();
            $role->rule()->saveAll($ruleIds);
            //关联部门
            $deptIds = $data['dept_ids'] ?? [];
            DeptModel::where('role_id', $data['id'])->delete();
            $role->dept()->saveAll($deptIds);
            self::commit();
        } catch (Exception $e) {
            self::rollback();
            abort('保存失败');
        }
    }
}

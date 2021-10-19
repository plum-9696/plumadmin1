<?php

namespace app\admin\model;

use app\common\model\AdminModel as Model;
use app\common\model\AdminRoleModel;
use app\common\model\RoleRuleModel;
use plum\core\exception\AuthException;
use plum\core\Token;
use think\Exception;
use think\exception\ValidateException;

class AdminModel extends Model
{

    /**
     * 登录
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 17:57
     */
    public static function login($data)
    {
        $admin = self::where('username', $data['username'])->find();
        if (!$admin || !password_verify($data['password'], $admin['password'])) {
            abort('账号或密码不正确');
        }
        if ($admin['status'] === self::STATUS_DISABLE) {
            abort('账号已禁用，请联系管理员');
        }
        return Token::build($admin['id'], 'admin');
    }

    /**
     * 新增
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 15:28
     */
    public static function add($data)
    {
        //校验
        $exists = self::where('username', $data['username'])->find();
        if ($exists) {
            abort('账号已存在');
        }
        //加密
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        try {
            self::startTrans();
            $admin = self::create($data);
            //关联角色
            $roleIds = $data['role_ids'] ?? [];
            $admin->role()->saveAll($roleIds);
            self::commit();
        } catch (Exception $e) {
            self::rollback();
            abort('保存失败');
        }
        return $admin;
    }

    /**
     * 修改
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 15:36
     */
    public static function edit($data)
    {
        //校验
        $exists = self::where('username', $data['username'])
            ->where('id', '<>', $data['id'])
            ->find();
        if ($exists) {
            abort('账号已存在');
        }
        //加密
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        try {
            self::startTrans();
            $admin = self::update($data);
            //关联角色
            $roleIds = $data['role_ids'] ?? [];
            AdminRoleModel::where('admin_id', $data['id'])->delete();
            $admin->role()->saveAll($roleIds);
            self::commit();
        } catch (Exception $e) {
            self::rollback();
            abort('保存失败');
        }
        return $admin;
    }

    /**
     * 详情
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 18:18
     */
    public static function detail($id)
    {
        $admin = self::find($id);
        if ($admin['status'] === self::STATUS_DISABLE) {
            abort('账号已禁用，请联系管理员');
        }
        return $admin;
    }

    /**
     * 获取当前用户的所有权限
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 19:43
     */
    public static function getRule($adminId)
    {
        $ruleModel = new RuleModel();
        if (config('plum.super_id') !== $adminId) {
            //非管理员,获取所有角色
            $roleIds = AdminRoleModel::where('admin_id', $adminId)->column('role_id');
            //获取所有权限
            $ruleIds = RoleRuleModel::whereIn('role_id', $roleIds)->column('rule_id');
            $ruleModel->whereIn('id', $ruleIds);
        }
        return $ruleModel->order('sort desc,id desc')->select();
    }

    /**
     * 获取用户信息
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 20:01
     */
    public static function getUserinfo($id)
    {
        $admin = self::find($id);
        if (!$admin) {
            throw new AuthException('用户不存在');
        }
        if ($admin->status === self::STATUS_DISABLE) {
            throw new AuthException('用户已禁用');
        }
        $admin->hidden(['password']);
        return $admin;
    }
}

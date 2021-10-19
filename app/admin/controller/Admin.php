<?php

namespace app\admin\controller;

use app\admin\model\AdminModel;
use app\admin\model\RuleModel;
use plum\core\Token;
use plum\lib\Utils;

class Admin extends Controller
{
    protected $whiteList = ['login', 'refreshToken'];
    protected $opLog = [
        'page'=>'查询用户列表'
    ];

    /**
     * 分页
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 17:05
     */
    public function page()
    {
        $page = AdminModel::with(['dept'])
            ->plumOrder()
            ->plumSearch()
            ->paginate()
            ->toArray();
        $page['data'] = Utils::toUrl($page['data'], ['avatar']);
        return $this->renderPage($page);
    }

    /**
     * 创建
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 17:05
     */
    public function create()
    {
        $data = $this->request->param();
        $this->validate($data, [
            'username|账号' => 'require',
            'password|密码' => 'require',
            'nickname|昵称' => 'require',
            'dept_id|部门'  => 'require',
            'status|状态'   => 'require',
        ]);
        AdminModel::add($data);
        return $this->renderSuccess();
    }

    /**
     * 修改
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 17:05
     */
    public function update()
    {
        $data = $this->request->param();
        $this->validate($data, [
            'id|ID'       => 'require',
            'username|账号' => 'require',
            'nickname|昵称' => 'require',
            'dept_id|部门'  => 'require',
            'status|状态'   => 'require',
        ]);
        AdminModel::edit($data);
        return $this->renderSuccess();
    }

    /**
     * 删除
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 17:05
     */
    public function delete()
    {
        $ids = $this->request->param('ids/a');
        AdminModel::destroy($ids);
        return $this->renderSuccess();
    }

    /**
     * 详情
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 17:05
     */
    public function info()
    {
        $info = AdminModel::find($this->request->param('id'));
        if (!$info) {
            abort('用户不存在');
        }
        $info->hidden(['password']);
        $info->append(['role_ids']);
        return $this->renderSuccess($info);
    }

    /**
     * 转移部门
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 17:16
     */
    public function moveDeptAll()
    {
        $data = $this->request->param();
        $this->validate($data, [
            'ids|ids'    => 'require',
            'dept_id|部门' => 'require',
        ]);
        AdminModel::update(['dept_id' => $data['dept_id']], [['id', 'in', $data['ids']]]);
        return $this->renderSuccess();
    }

    /**
     * 登录
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 17:34
     */
    public function login()
    {
        $data = $this->request->param();
        $this->validate($data, [
            'username|账号' => 'require',
            'password|密码' => 'require',
        ]);
        return $this->renderSuccess(AdminModel::login($data));
    }

    /**
     * 登出
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 17:52
     */
    public function logout()
    {
        Token::invalid('admin');
        return $this->renderSuccess();
    }

    /**
     * 用户信息
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 18:16
     */
    public function userinfo()
    {
        $info = $this->admin->toArray();
        $info = Utils::toUrl($info, ['avatar as avatar_url']);
        return $this->renderSuccess($info);
    }

    /**
     * 修改用户信息
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月14日 16:10
     */
    public function userinfoUpdate()
    {
        $info = $this->admin;
        $data = $this->request->only(['nickname', 'password', 'avatar']);
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        $info->save($data);
        return $this->renderSuccess();
    }

    /**
     * 获取所有的权限菜单，数据由前端处理
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 19:31
     */
    public function permission()
    {
        $rule = AdminModel::getRule($this->admin->id)->toArray();
        $menus = array_values(array_filter($rule, function ($item) {
            return $item['type'] === RuleModel::TYPE_DIR || $item['type'] === RuleModel::TYPE_MENU;
        }));
        $perms = array_values(array_filter($rule, function ($item) {
            return $item['type'] === RuleModel::TYPE_PERM;
        }));
        return $this->renderSuccess(compact('menus', 'perms'));
    }

    /**
     * 刷新token
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月07日 17:25
     */
    public function refreshToken()
    {
        $data = Token::refresh('admin');
        return $this->renderSuccess($data);
    }


}

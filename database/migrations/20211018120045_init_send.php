<?php

use think\migration\Migrator;
use think\migration\db\Column;

class InitSend extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        //初始化账号数据
        $this->insert('admin', [
            [
                'id'          => 1,
                'username'    => 'admin',
                'password'    => '$2y$10$yRAbOHmNxodcMo0IrNuPj.0K7b8PTpSsBpwViPfbDb583j59IWLo2',
                'dept_id'     => 0,
                'status'      => 1
            ],
        ]);
        //初始化菜单数据
        $this->insert('rule', [
            [
                'id' => 8,
                'type' => 1,
                'path' => '/sys/menu',
                'name' => '菜单列表',
                'parent_id' => 27,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => 'cool/modules/base/views/menu.vue',
                'icon' => 'icon-menu',
                'perms' => '',
                'sort' => 80
            ],
            [
                'id' => 22,
                'type' => 1,
                'path' => '/sys/role',
                'name' => '角色列表',
                'parent_id' => 27,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => 'cool/modules/base/views/role.vue',
                'icon' => 'icon-role',
                'perms' => '',
                'sort' => 90
            ],
            [
                'id' => 27,
                'type' => 0,
                'path' => '',
                'name' => '权限管理',
                'parent_id' => 2,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => '',
                'icon' => 'icon-auth',
                'perms' => '',
                'sort' => 95
            ],
            [
                'id' => 97,
                'type' => 1,
                'path' => '/sys/user',
                'name' => '用户列表',
                'parent_id' => 27,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => 'cool/modules/base/views/user.vue',
                'icon' => 'icon-user',
                'perms' => '',
                'sort' => 100
            ],
            [
                'id' => 109,
                'type' => 2,
                'path' => '',
                'name' => '新增',
                'parent_id' => 22,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'role@create',
                'sort' => 0
            ],
            [
                'id' => 110,
                'type' => 2,
                'path' => '',
                'name' => '修改',
                'parent_id' => 22,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'role@update',
                'sort' => 0
            ],
            [
                'id' => 111,
                'type' => 2,
                'path' => '',
                'name' => '删除',
                'parent_id' => 22,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'role@delete',
                'sort' => 0
            ],
            [
                'id' => 112,
                'type' => 2,
                'path' => '',
                'name' => '新增',
                'parent_id' => 8,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'rule@create',
                'sort' => 0
            ],
            [
                'id' => 113,
                'type' => 2,
                'path' => '',
                'name' => '编辑',
                'parent_id' => 8,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'rule@update',
                'sort' => 0
            ],
            [
                'id' => 114,
                'type' => 2,
                'path' => '',
                'name' => '删除',
                'parent_id' => 8,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'role@delete',
                'sort' => 0
            ],
            [
                'id' => 132,
                'type' => 2,
                'path' => '',
                'name' => '列表',
                'parent_id' => 22,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'role@page',
                'sort' => 0
            ],
            [
                'id' => 133,
                'type' => 2,
                'path' => '',
                'name' => '列表',
                'parent_id' => 8,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'rule@tree',
                'sort' => 0
            ],
            [
                'id' => 136,
                'type' => 0,
                'path' => '',
                'name' => '内容管理',
                'parent_id' => 0,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => 'icon-content',
                'perms' => '',
                'sort' => 90
            ],
            [
                'id' => 137,
                'type' => 1,
                'path' => '/centent/file',
                'name' => '文件管理',
                'parent_id' => 136,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => 'cool/modules/base/views/file.vue',
                'icon' => 'icon-file',
                'perms' => '',
                'sort' => 0
            ],
            [
                'id' => 138,
                'type' => 1,
                'path' => '/dashboard',
                'name' => '仪表盘',
                'parent_id' => 0,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => 'cool/modules/base/views/file.vue',
                'icon' => 'icon-dashboard',
                'perms' => '',
                'sort' => 100
            ],
            [
                'id' => 139,
                'type' => 0,
                'path' => '',
                'name' => '监控管理',
                'parent_id' => 0,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => 'icon-monitor2',
                'perms' => '',
                'sort' => 75
            ],
            [
                'id' => 141,
                'type' => 1,
                'path' => '/monitor/queue',
                'name' => '消息队列',
                'parent_id' => 139,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => 'cool/modules/base/views/queue-log.vue',
                'icon' => 'icon-queue',
                'perms' => '',
                'sort' => 100
            ],
            [
                'id' => 142,
                'type' => 0,
                'path' => '',
                'name' => '系统设置',
                'parent_id' => 0,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => 'icon-system',
                'perms' => '',
                'sort' => 80
            ],
            [
                'id' => 143,
                'type' => 1,
                'path' => '/setting/website',
                'name' => '站点设置',
                'parent_id' => 142,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => 'cool/modules/base/views/website-setting.vue',
                'icon' => 'icon-website-setting',
                'perms' => '',
                'sort' => 110
            ],
            [
                'id' => 144,
                'type' => 1,
                'path' => '/setting/upload',
                'name' => '上传设置',
                'parent_id' => 142,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => 'cool/modules/base/views/upload-setting.vue',
                'icon' => 'icon-uplaod',
                'perms' => '',
                'sort' => 100
            ],
            [
                'id' => 145,
                'type' => 1,
                'path' => '/monitor/debug',
                'name' => '错误日志',
                'parent_id' => 139,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => 'cool/modules/base/views/debug-log.vue',
                'icon' => 'icon-debug',
                'perms' => '',
                'sort' => 110
            ],
            [
                'id' => 146,
                'type' => 1,
                'path' => '/monitor/operation',
                'name' => '操作日志',
                'parent_id' => 139,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => 'cool/modules/base/views/operation-log.vue',
                'icon' => 'icon-operation',
                'perms' => '',
                'sort' => 110
            ],
            [
                'id' => 147,
                'type' => 1,
                'path' => '/setting/cache',
                'name' => '清理缓存',
                'parent_id' => 142,
                'keep_alive' => 0,
                'is_show' => 1,
                'view_path' => 'cool/modules/base/views/cache-setting.vue',
                'icon' => 'icon-clear',
                'perms' => '',
                'sort' => 80
            ],
            [
                'id' => 148,
                'type' => 2,
                'path' => '',
                'name' => '部门 - 新增',
                'parent_id' => 97,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'dept@create',
                'sort' => 20
            ],
            [
                'id' => 149,
                'type' => 2,
                'path' => '',
                'name' => '部门 - 修改',
                'parent_id' => 97,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'dept@update',
                'sort' => 20
            ],
            [
                'id' => 150,
                'type' => 2,
                'path' => '',
                'name' => '部门 - 删除',
                'parent_id' => 97,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'dept@delete',
                'sort' => 20
            ],
            [
                'id' => 151,
                'type' => 2,
                'path' => '',
                'name' => '部门 - 列表',
                'parent_id' => 97,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'dept@tree',
                'sort' => 20
            ],
            [
                'id' => 152,
                'type' => 2,
                'path' => '',
                'name' => '部门 - 排序',
                'parent_id' => 97,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'dept@sort',
                'sort' => 20
            ],
            [
                'id' => 153,
                'type' => 2,
                'path' => '',
                'name' => '部门 - 详情',
                'parent_id' => 97,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'dept@info',
                'sort' => 20
            ],
            [
                'id' => 154,
                'type' => 2,
                'path' => '',
                'name' => '用户 - 新增',
                'parent_id' => 97,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'admin@create',
                'sort' => 30
            ],
            [
                'id' => 155,
                'type' => 2,
                'path' => '',
                'name' => '用户 - 修改',
                'parent_id' => 97,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'admin@update',
                'sort' => 30
            ],
            [
                'id' => 156,
                'type' => 2,
                'path' => '',
                'name' => '用户 - 删除',
                'parent_id' => 97,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'admin@delete',
                'sort' => 30
            ],
            [
                'id' => 157,
                'type' => 2,
                'path' => '',
                'name' => '用户 - 列表',
                'parent_id' => 97,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'admin@page',
                'sort' => 30
            ],
            [
                'id' => 158,
                'type' => 2,
                'path' => '',
                'name' => '用户 - 详情',
                'parent_id' => 97,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'admin@info',
                'sort' => 30
            ],
            [
                'id' => 159,
                'type' => 2,
                'path' => '',
                'name' => '用户 - 转移部门',
                'parent_id' => 97,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'admin@moveDeptAll',
                'sort' => 30
            ],
            [
                'id' => 160,
                'type' => 2,
                'path' => '',
                'name' => '详细',
                'parent_id' => 22,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'role@info',
                'sort' => 0
            ],
            [
                'id' => 161,
                'type' => 2,
                'path' => '',
                'name' => '详细',
                'parent_id' => 8,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'rule@info',
                'sort' => 0
            ],
            [
                'id' => 162,
                'type' => 2,
                'path' => '',
                'name' => '文件 - 上传',
                'parent_id' => 137,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'file@upload',
                'sort' => 0
            ],
            [
                'id' => 163,
                'type' => 2,
                'path' => '',
                'name' => '文件 - 列表',
                'parent_id' => 137,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'file@page',
                'sort' => 0
            ],
            [
                'id' => 164,
                'type' => 2,
                'path' => '',
                'name' => '文件 - 删除',
                'parent_id' => 137,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'file@delete',
                'sort' => 0
            ],
            [
                'id' => 165,
                'type' => 2,
                'path' => '',
                'name' => '文件 - 转移分组',
                'parent_id' => 137,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'file@moveGroup',
                'sort' => 0
            ],
            [
                'id' => 166,
                'type' => 2,
                'path' => '',
                'name' => '文件分组 - 列表',
                'parent_id' => 137,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'file.group@tree',
                'sort' => 0
            ],
            [
                'id' => 167,
                'type' => 2,
                'path' => '',
                'name' => '文件分组 - 新增',
                'parent_id' => 137,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'file.group@create',
                'sort' => 0
            ],
            [
                'id' => 168,
                'type' => 2,
                'path' => '',
                'name' => '文件分组 - 修改',
                'parent_id' => 137,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'file.group@update',
                'sort' => 0
            ],
            [
                'id' => 169,
                'type' => 2,
                'path' => '',
                'name' => '文件分组 - 删除',
                'parent_id' => 137,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'file.group@delete',
                'sort' => 0
            ],
            [
                'id' => 170,
                'type' => 2,
                'path' => '',
                'name' => '文件分组 - 排序',
                'parent_id' => 137,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'file.group@delete',
                'sort' => 0
            ],
            [
                'id' => 171,
                'type' => 2,
                'path' => '',
                'name' => '详情',
                'parent_id' => 143,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'setting@get',
                'sort' => 0
            ],
            [
                'id' => 172,
                'type' => 2,
                'path' => '',
                'name' => '保存',
                'parent_id' => 143,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'setting@set',
                'sort' => 0
            ],
            [
                'id' => 173,
                'type' => 2,
                'path' => '',
                'name' => '保存',
                'parent_id' => 144,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'setting@set',
                'sort' => 0
            ],
            [
                'id' => 174,
                'type' => 2,
                'path' => '',
                'name' => '详情',
                'parent_id' => 144,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'setting@get',
                'sort' => 0
            ],
            [
                'id' => 175,
                'type' => 2,
                'path' => '',
                'name' => '清理',
                'parent_id' => 147,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'common@clearCache',
                'sort' => 0
            ],
            [
                'id' => 176,
                'type' => 2,
                'path' => '',
                'name' => '获取缓存项',
                'parent_id' => 147,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'common@cacheTag',
                'sort' => 0
            ],
            [
                'id' => 177,
                'type' => 2,
                'path' => '',
                'name' => '列表',
                'parent_id' => 146,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'log.operation@page',
                'sort' => 0
            ],
            [
                'id' => 178,
                'type' => 2,
                'path' => '',
                'name' => '清理',
                'parent_id' => 146,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'log.operation@clear',
                'sort' => 0
            ],
            [
                'id' => 179,
                'type' => 2,
                'path' => '',
                'name' => '删除',
                'parent_id' => 146,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'log.operation@delete',
                'sort' => 0
            ],
            [
                'id' => 180,
                'type' => 2,
                'path' => '',
                'name' => '列表',
                'parent_id' => 145,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'log.debug@page',
                'sort' => 0
            ],
            [
                'id' => 181,
                'type' => 2,
                'path' => '',
                'name' => '删除',
                'parent_id' => 145,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'log.debug@delete',
                'sort' => 0
            ],
            [
                'id' => 182,
                'type' => 2,
                'path' => '',
                'name' => '清空',
                'parent_id' => 145,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'log.debug@clear',
                'sort' => 0
            ],
            [
                'id' => 183,
                'type' => 2,
                'path' => '',
                'name' => '列表',
                'parent_id' => 141,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'log.queue@page',
                'sort' => 0
            ],
            [
                'id' => 184,
                'type' => 2,
                'path' => '',
                'name' => '清空',
                'parent_id' => 141,
                'keep_alive' => 1,
                'is_show' => 1,
                'view_path' => '',
                'icon' => '',
                'perms' => 'log.queue@clear',
                'sort' => 0
            ],
        ]);
    }
}
<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Rule extends Migrator
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
        $this->table('rule', ['comment' => '权限表'])
            ->addColumn('type', 'integer', ['comment' => '类型(0-目录 1-菜单 2-权限)', 'default' => 0, 'length' => 1])
            ->addColumn('path', 'string', ['comment' => '路径', 'default' => ''])
            ->addColumn('name', 'string', ['comment' => '菜单名称', 'default' => ''])
            ->addColumn('parent_id', 'integer', ['comment' => '上级ID', 'default' => 0])
            ->addColumn('keep_alive', 'integer', ['comment' => '缓存(bool)', 'default' => 1])
            ->addColumn('is_show', 'integer', ['comment' => '是否显示', 'default' => 1])
            ->addColumn('view_path', 'string', ['comment' => '文件路径', 'default' => ''])
            ->addColumn('icon', 'string', ['comment' => '图标', 'default' => ''])
            ->addColumn('perms', 'string', ['comment' => '权限标识', 'default' => ''])
            ->addColumn('sort', 'integer', ['comment' => '排序(降序)', 'default' => 0])
            ->addTimestamps()
            ->save();
    }
}

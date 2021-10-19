<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Admin extends Migrator
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
        $this->table('admin', ['comment' => '管理员表'])
            ->addColumn('avatar', 'integer', ['default' => 0, 'comment' => '头像'])
            ->addColumn('username', 'string', ['length' => 30, 'default' => '', 'comment' => '账号'])
            ->addColumn('password', 'string', ['default' => '', 'comment' => '密码'])
            ->addColumn('nickname', 'string', ['length' => 30, 'default' => '', 'comment' => '昵称'])
            ->addColumn('phone', 'string', ['length' => 20, 'default' => '', 'comment' => '手机号'])
            ->addColumn('email', 'string', ['length' => 30, 'default' => '', 'comment' => '邮箱'])
            ->addColumn('remark', 'string', ['default' => '', 'comment' => '备注'])
            ->addColumn('dept_id', 'integer', ['default' => 0, 'comment' => '部门ID'])
            ->addColumn('status', 'integer', ['length' => 1, 'default' => 1, 'comment' => '状态（1-正常 2-拉黑）'])
            ->addTimestamps()
            ->addSoftDelete()
            ->save();
    }
}

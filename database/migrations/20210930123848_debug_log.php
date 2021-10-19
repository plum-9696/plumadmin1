<?php

use think\migration\Migrator;
use think\migration\db\Column;

class DebugLog extends Migrator
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
        $this->table('debug_log', ['comment' => '错误日志表'])
            ->addColumn('message', 'string', ['comment' => '错误信息'])
            ->addColumn('file', 'string', ['comment' => '文件'])
            ->addColumn('line', 'string', ['comment' => '行数'])
            ->addColumn('trace', 'text', ['comment' => '跟踪信息'])
            ->addColumn('param', 'text', ['comment' => '参数'])
            ->addColumn('method','string',['comment'=>'请求方法','default'=>'','limit'=>20])
            ->addColumn('header', 'text', ['comment' => '请求头'])
            ->addTimestamps()
            ->save();
    }
}

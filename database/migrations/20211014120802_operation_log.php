<?php

use think\migration\Migrator;
use think\migration\db\Column;

class OperationLog extends Migrator
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
        $this->table('operation_log')
            ->addColumn('message','string',['comment'=>'操作行为','default'=>''])
            ->addColumn('route','string',['comment'=>'路由,控制器@方法','default'=>''])
            ->addColumn('ip','string',['comment'=>'ip地址','default'=>''])
            ->addColumn('operator_id','integer',['comment'=>'操作员','default'=>0])
            ->addColumn('param','text',['comment'=>'请求参数'])
            ->addTimestamps()
            ->save();
    }
}

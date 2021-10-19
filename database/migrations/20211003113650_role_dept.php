<?php

use think\migration\Migrator;
use think\migration\db\Column;

class RoleDept extends Migrator
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
        $this->table('role_dept',['comment'=>'角色_部门中间表'])
            ->addColumn('role_id','integer',['comment'=>'角色ID','default'=>0])
            ->addColumn('dept_id','integer',['comment'=>'部门ID','default'=>0])
            ->save();
    }
}

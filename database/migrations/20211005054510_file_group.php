<?php

use think\migration\Migrator;
use think\migration\db\Column;

class FileGroup extends Migrator
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
        $this->table('file_group',['comment'=>'文件分组'])
            ->addColumn('name','string',['comment'=>"分组名",'length'=>30,'default'=>''])
            ->addColumn('parent_id','integer',['comment'=>"父级id",'default'=>0])
            ->addColumn('sort','integer',['comment'=>"父级id",'default'=>0])
            ->addTimestamps()
            ->save();
    }
}

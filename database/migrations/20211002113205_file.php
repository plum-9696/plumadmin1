<?php

use think\migration\Migrator;
use think\migration\db\Column;

class File extends Migrator
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
        $this->table('file',['comment'=>'文件表'])
            ->addColumn('group_id','integer',['comment'=>'文件分组ID','default'=>0])
            ->addColumn('channel','integer',['comment'=>'上传来源(1系统后台)','default'=>1,'limit'=>1])
            ->addColumn('storage','string',['comment'=>'存储方式','default'=>''])
            ->addColumn('file_type','string',['comment'=>'文件类型(image video file)','default'=>'','limit'=>20])
            ->addColumn('file_name','string',['comment'=>'文件名称(','default'=>''])
            ->addColumn('file_path','string',['comment'=>'文件路径','default'=>''])
            ->addColumn('file_size','integer',['comment'=>'文件大小(字节)','default'=>0])
            ->addColumn('file_ext','string',['comment'=>'文件扩展名','default'=>''])
            ->addColumn('uploader_id','integer',['comment'=>'上传者用户ID','default'=>0])
            ->addTimestamps()
            ->save();
    }
}

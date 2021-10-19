<?php

namespace app\common\model\file;

use app\common\model\BaseModel;

class GroupModel extends BaseModel
{
    protected $table = 'file_group';
    protected $autoWriteTimestamp = 'timestamp';
}

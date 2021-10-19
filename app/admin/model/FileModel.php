<?php

namespace app\admin\model;

use app\common\model\FileModel as Model;
use plum\core\Token;

class FileModel extends Model
{
    /**
     * 通道
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 00:45
     */
    protected static function channel()
    {
        return self::CHANNEL_ADMIN;
    }

    /**
     * 上传者
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 00:45
     */
    protected static function uploaderId()
    {
        return Token::auth('admin', false);
    }
}

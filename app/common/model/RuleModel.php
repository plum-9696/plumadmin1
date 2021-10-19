<?php

namespace app\common\model;

class RuleModel extends BaseModel
{
    protected $table = 'rule';
    protected $autoWriteTimestamp = 'timestamp';
    protected $type = ['is_show'=>'boolean','keep_alive'=>'boolean'];

    const TYPE_DIR = 0;
    const TYPE_MENU = 1;
    const TYPE_PERM = 2;

    /**
     * 搜索器 - keyword
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 22:52
     */
    public function searchKeywordAttr($query, $value)
    {
        $query->whereLike('name', "%{$value}%");
    }

    /**
     * 搜索器 - type
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月06日 15:33
     */
    public function searchTypeAttr($query,$value)
    {
        if(is_string($value)){
            $query->where('type',$value);
        }
        if(is_array($value)){
            $query->whereIn('type',$value);
        }
    }
}

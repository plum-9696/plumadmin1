<?php

namespace plum\core;

use plum\lib\Str;
use think\db\exception\DataNotFoundException;
use think\db\Query;
use think\Paginator;

class PlumQuery extends Query
{
    /**
     * 分页
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 19:53
     */
    public function paginate($listRows = null, $simple = false): Paginator
    {
        //重写每页页数
        if (!$listRows) {
            $listRows = input('size', 15);
        }
        return parent::paginate($listRows, $simple);
    }

    /**
     * 排序
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 20:53
     */
    public function plumOrder($sort = 'desc'): PlumQuery
    {
        if (input('order') && input('sort') && in_array(input('order'), array_keys($this->getFields()))) {
            $this->order($this->getTable() . '.' . input('order') . '', input('sort'));
        } else {
            //获取当前的字段
            if (in_array('sort', array_keys($this->getFields()))) {
                $this->order($this->getTable() . '.sort', $sort);
            }
            if (in_array('create_time', array_keys($this->getFields()))) {
                $this->order($this->getTable() . '.create_time', $sort);
            }else{
                $this->order($this->getTable() . '.' . $this->getPk(), $sort);
            }
        }
        return $this;
    }

    /**
     * 搜索
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 20:54
     */
    public function plumSearch(): PlumQuery
    {
        $params = empty($params) ? request()->param() : $params;

        if (empty($params)) {
            return $this;
        }

        foreach ($params as $field => $value) {
            $method = 'search' . Str::studly($field) . 'Attr';
            if ($value !== null && $value !== '' && $value !== [] && method_exists($this->model, $method)) {
                $this->model->$method($this, $value, $params);
            }
        }
        return $this;
    }
}

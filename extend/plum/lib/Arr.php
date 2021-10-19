<?php

namespace plum\lib;

use think\helper\Arr as Base;

/**
 * @method static Base accessible($value) 函数检查给定的值是否可数组式访问
 * @method static Base add($array, $key, $value) 如果给定的键在数组中不存在或给定的键的值被设置为    null ，那么 Arr::add 函数将会把给定的键值对添加到数组中
 * @method static Base collapse($array) 函数将多个数组合并为一个数组
 * @method static Base crossJoin(...$arrays) 函数交叉连接给定的数组，返回具有所有可能排列的笛卡尔乘积
 * @method static Base divide($array)  函数返回一个二维数组，一个值包含原数组的键，另一个值包含原数组的值
 * @method static Base dot($array, $prepend = '')  函数将多维数组中所有的键平铺到一维数组中，新数组使用「.」符号表示层级包含关系
 * @method static Base except($array, $keys)  函数从数组中删除指定的键值对
 * @method static Base exists($array, $key) 检查给定的键是否存在提供的数组中
 * @method static Base first($array, callable $callback = null, $default = null) 函数返回数组中满足指定条件的第一个元素
 * @method static Base last($array, callable $callback = null, $default = null) 函数返回数组中满足指定条件的最后一个元素
 * @method static Base flatten($array, $depth = INF) 函数将多维数组中数组的值取出平铺为一维数组
 * @method static Base forget(&$array, $keys) 函数使用「.」符号从深度嵌套的数组中删除给定的键值对
 * @method static Base get($array, $key, $default = null) 函数使用「.」符号从深度嵌套的数组根据指定键检索值
 * @method static Base has($array, $keys) 函数使用「.」符号判断数组中是否存在指定的一个或多个键
 * @method static Base isAssoc(array $array) 如果给定数组是关联数组，则 Arr::isAssoc 函数返回 true ，如果数组没有以零开头的连续数字键，则将其视为「关联」
 * @method static Base only($array, $keys) 函数只返回给定数组中指定的键值对
 * @method static Base pluck($array, $value, $key = null)  函数从数组中检索给定键的所有值
 * @method static Base prepend($array, $value, $key = null) 函数将一个值插入到数组的开始位置
 * @method static Base pull(&$array, $key, $default = null) 函数从数组中返回指定键的值并删除此键值对
 * @method static Base random($array, $number = null) 函数从数组中随机返回一个值
 * @method static Base set(&$array, $key, $value) 函数使用「.」符号在多维数组中设置指定键的值
 * @method static Base shuffle($array, $seed = null) 函数将数组中值进行随机排序
 * @method static Base sort($array, $callback = null) 函数根据数组的值大小进行排序
 * @method static Base sortRecursive($array) 函数使用 sort 函数对数值子数组进行递归排序，使用 ksort 函数对关联子数组进行递归排序
 * @method static Base query($array) 函数将数组转换为查询字符串
 * @method static Base where($array, callable $callback) 函数使用给定的回调函数返回的结果过滤数组
 * @method static Base wrap($value) 函数可以将给定值转换为一个数组
 */
class Arr extends Base
{
    /**
     * 转化树状结构
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年09月30日 15:12
     */
    public static function tree($data): array
    {
        if ($data instanceof \ArrayAccess) {
            $data = $data->toArray();
        }
        $data = Arr::pluck($data, null, 'id');
        $tree = [];
        foreach ($data as &$v) {
            if (isset($data[$v['parent_id']])) {
                //父类存在进入父类的children下
                $data[$v['parent_id']]['children'][] = &$v;
            } else {
                //父类不存在进入tree数组
                $tree[] = &$v;
            }
        }
        return $tree;
    }


    /**
     * 多维数组合并
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月01日 22:02
     */
    public static function mergeMultiple($array1, $array2): array
    {
        $merge = $array1 + $array2;
        $data = [];
        foreach ($merge as $key => $val) {
            if (
                isset($array1[$key])
                && is_array($array1[$key])
                && isset($array2[$key])
                && is_array($array2[$key])
            ) {
                $data[$key] = self::isAssoc($array1[$key]) ? self::mergeMultiple($array1[$key], $array2[$key]) : $array2[$key];
            } else {
                $data[$key] = $array2[$key] ?? $array1[$key];
            }
        }
        return $data;
    }

    /**
     * 不区分大小写
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月07日 21:54
     */
    public static function inArray($needle, $array)
    {
        $array = array_filter($array, function ($item) use ($needle) {
            if (is_string($item)) {
                return strcasecmp($item, $needle) === 0;
            } else {
                return $item == $needle;
            }
        });
        return count($array) > 0;
    }
}

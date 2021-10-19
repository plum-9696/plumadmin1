<?php

namespace plum\lib;

use app\common\model\FileModel;
use think\facade\Cache;
use think\facade\Db;

class Utils
{
    /**
     * 获取子ID
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 17:26
     */
    public static function getChildrenIds($obj, $ids)
    {
        $childrenIds = $obj->whereIn('parent_id', $ids)->column('id');
        if (!empty($childrenIds)) {
            $childrenIds = array_merge($childrenIds, self::getChildrenIds($obj, $childrenIds));
        }
        return $childrenIds;
    }

    /**
     * 获取当前文件夹下所有的文件
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月06日 19:02
     */
    public static function getAllFile($path, $extension)
    {
        $result = [];
        $pattern = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*';
        $files = glob($pattern);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $result = array_merge($result, self::getAllFile($file, $extension));
            } elseif (strcasecmp(pathinfo($file, PATHINFO_EXTENSION), $extension) === 0) {
                array_push($result, $file);
            }
        }
        return $result;
    }

    /**
     * 转化连接
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月14日 15:42
     */
    public static function toUrl($data, $fields)
    {
        //整理field
        $fields = array_values(array_map(function ($item) {
            $data = explode(' as ', $item);
            return [
                'key'   => trim($data[0]),
                'alias' => trim($data[1] ?? $data[0])
            ];
        }, $fields));
        // 判断是关联数组还是索数组
        $isAssoc = Arr::isAssoc($data);
        if ($isAssoc) {
            $data = [$data];
        }
        //收集所有的id
        $fileIds = [];
        foreach ($data as $item) {
            foreach ($fields as $field) {
                //如果存在且不为空,则插入
                if (!empty($item[$field['key']])) {
                    if (is_array($item[$field['key']])) {
                        array_merge($fileIds, $item[$field['key']]);
                    } else {
                        array_push($fileIds, $item[$field['key']]);
                    }
                }
            }
        }
        $fileIds = array_values(array_unique($fileIds));
        //获取图片
        $files = FileModel::whereIn('id', $fileIds)->select()->toArray();
        $files = array_column($files, null, 'id');
        //循环插入
        foreach ($data as &$item) {
            foreach ($fields as $field) {
                if (isset($item[$field['key']])) {
                    //如果有实值,则真正插入
                    if (!empty($item[$field['key']])) {
                        if (is_array($item[$field['key']])) {
                            $value = array_map(function ($id) use ($files) {
                                return $files[$id]['url'] ?? '';
                            }, $item[$field['key']]);
                        } else {
                            $value = $files[$item[$field['key']]]['url'] ?? '';
                        }
                        $item[$field['alias']] = $value;
                    } else {
                        //默认空字符或者空数组
                        $item[$field['alias']] = is_array($item[$field['key']]) ? [] : '';
                    }
                }
            }
        }
        //返回值
        return $isAssoc ? $data[0] : $data;
    }

    /**
     * 记录错误日志
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月14日 20:23
     */
    public static function recordError($exception, $attr = '')
    {
        try {
            $message = $attr ? $exception->getMessage() . "【附加】:" . $attr : $exception->getMessage();
            Db::table('debug_log')->insert([
                'message' => $message,
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                'trace'   => $exception->getTraceAsString(),
                'param'   => json_encode(request()->param(), JSON_UNESCAPED_UNICODE),
                'header'  => json_encode(request()->header(), JSON_UNESCAPED_UNICODE),
                'method'  => request()->method()
            ]);
        } catch (\Exception $e) {
        }
    }
}

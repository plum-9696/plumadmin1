<?php

namespace app\admin\service;

use think\facade\Cache;

class QueueService
{
    const KEY = 'redis-queue-failed';

    /**
     * 失败队列的分页
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月18日 13:22
     */
    public static function page()
    {
        $page = input('page', 1);
        $size = input('size', 20);
        $redis = Cache::store('redis')->handler();
        $start = ($page - 1) * $size;
        $end = $start + $size - 1;
        $list = $redis->lRange(self::KEY, $start, $end);
        $list = array_map(function ($item) {
            $item = json_decode($item, true);
            $item['time'] = date('Y-m-d H:i:s', $item['time']);
            $item['data'] = json_encode($item['data']);
            return $item;
        }, $list);
        return [
            'data'  => $list,
            'current_page'  => $page,
            'per_page'  => $size,
            'total' => $redis->lLen(self::KEY)
        ];
    }

    /**
     * 清理队列（可重新入队列）
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月18日 13:22
     */
    public static function clear($send)
    {
        $redis = Cache::store('redis')->handler();
        $data = [];
        while (($item = $redis->rPop(self::KEY)) !== false) {
            array_push($data, json_decode($item, true));
        }
        if ($send) {
            //入队列
            $config = config('plum.queue');
            $queue = [];
            foreach ($config as $q) {
                $object = app()->make($q);
                $queue[$object->name()] = $object;
            }
            foreach ($data as $v) {
                $object = $queue[$v['queue']];
                $object->send($v['data'], $v['delay']);
            }
        }
    }
}

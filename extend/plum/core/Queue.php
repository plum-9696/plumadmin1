<?php

namespace plum\core;

use plum\lib\Log;
use think\facade\Cache;

abstract class Queue
{
    abstract public function handler($data);

    abstract public function name();

    public function execute($data)
    {
        $this->handler($data);
    }

    public function send($data, $delay = 0)
    {
        $redis = Cache::store('redis')->handler();
        $queue_waiting = 'redis-queue-waiting';
        $queue_delay = 'redis-queue-delayed';
        $now = time();
        $package_str = json_encode([
            'id'       => rand(),
            'time'     => $now,
            'delay'    => 0,
            'attempts' => 0,
            'queue'    => $this->name(),
            'data'     => $data
        ]);
        if ($delay) {
            return $redis->zAdd($queue_delay, $now + $delay, $package_str);
        }
        return $redis->lPush($queue_waiting . $this->name(), $package_str);
    }
}

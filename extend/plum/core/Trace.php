<?php

namespace plum\core;

class Trace
{
    protected $config = [
        'file' => '',
        'tabs' => ['base' => '基本', 'notice|error' => '错误', 'sql' => 'SQL', 'info' => '流程', 'debug|log' => '调试',
                   'file' => '文件'],
    ];

    public function traceDebug()
    {
        $app = app();
        $request = request();
        $this->config = array_merge($this->config, config('trace'));

        // 获取基本信息
        $runtime = number_format(microtime(true) - $app->getBeginTime(), 10, '.', '');
        $reqs = $runtime > 0 ? number_format(1 / $runtime, 2) : '∞';
        $mem = number_format((memory_get_usage() - $app->getBeginMem()) / 1024, 2);

        // 页面Trace信息
        if ($request->host()) {
            $uri = $request->protocol() . ' ' . $request->method() . ' : ' . $request->url(true);
        } else {
            $uri = 'cmd:' . implode(' ', $_SERVER['argv']);
        }

        $base = [
            '请求信息' => date('Y-m-d H:i:s', $request->time() ?: time()) . ' ' . $uri,
            '运行时间' => number_format((float)$runtime, 6) . 's [ 吞吐率：' . $reqs . 'req/s ] 内存消耗：' . $mem . 'kb 文件加载：' . count(get_included_files()),
            '查询信息' => $app->db->getQueryTimes() . ' queries',
            '缓存信息' => $app->cache->getReadTimes() . ' reads,' . $app->cache->getWriteTimes() . ' writes',
        ];

        if (isset($app->session)) {
            $base['会话信息'] = 'SESSION_ID=' . $app->session->getId();
        }

        $info = $this->getFileInfo();

        // 页面Trace信息
        $trace = [];
        $log = $app->log->getLog();
        foreach ($this->config['tabs'] as $name => $title) {
            $name = strtolower($name);
            switch ($name) {
                case 'base': // 基本信息
                    $trace[$title] = $base;
                    break;
                case 'file': // 文件信息
                    $trace[$title] = $info;
                    break;
                default: // 调试信息
                    if (strpos($name, '|')) {
                        // 多组信息
                        $names = explode('|', $name);
                        $result = [];
                        foreach ($names as $item) {
                            $result = array_merge($result, $log[$item] ?? []);
                        }
                        $trace[$title] = $result;
                    } else {
                        $trace[$title] = $log[$name] ?? '';
                    }
            }
        }
        return $trace;
    }

    /**
     * 获取文件加载信息
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年09月30日 19:20
     */
    protected function getFileInfo(): array
    {
        $files = get_included_files();
        $info = [];

        foreach ($files as $key => $file) {
            $info[] = $file . ' ( ' . number_format(filesize($file) / 1024, 2) . ' KB )';
        }

        return $info;
    }
}

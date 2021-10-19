<?php
declare (strict_types=1);

namespace app\command;

use think\cache\driver\Redis;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\Exception;
use think\facade\Event;
use Workerman\RedisQueue\Client;
use Workerman\Worker;

class Timer extends Command
{
    protected $timer;

    protected function configure()
    {
        // 指令配置
        $this->setName('timer')
            ->addArgument('status', Argument::REQUIRED, 'start/stop/reload/status/connections')
            ->addOption('d', null, Option::VALUE_NONE, 'daemon（守护进程）方式启动')
            ->setDescription('start/stop/restart 定时任务');
    }

    protected function execute(Input $input, Output $output)
    {
        $this->init($input, $output);
        // 创建定时器任务
        $worker = new Worker();
        $worker->onWorkerStart = [$this, 'start'];
        $worker->runAll();
    }

    protected function init(Input $input, Output $output)
    {
        global $argv;
        $argv[1] = $input->getArgument('status') ?: 'start';
        if ($input->hasOption('d')) {
            $argv[2] = '-d';
        } else {
            unset($argv[2]);
        }
    }

    /**
     * 定时器执行的内容
     * @return false|int
     */
    public function start($worker)
    {
        $time = time();
        $timer = config('plum.timer');
        $queue = config('plum.queue');
        //执行队列
        foreach ($queue as $v) {
            $config = config('cache.stores.redis');
            $client = new Client("redis://{$config['host']}:{$config['port']}", [
                'auth'          => $config['password'],
                'db'            => $config['select'],
                'max_attempts'  => 5,
                'retry_seconds' => 5
            ]);
            $obj = app()->make($v);
            $client->subscribe($obj->name(), function ($data) use ($obj) {
                $obj->execute($data);
            });
        }
        //执行定时任务
        foreach ($timer as $v) {
            $obj = app()->make($v);
            \Workerman\Lib\Timer::add(1, function () use ($obj) {
                call_user_func_array([$obj, 'execute'], []);
            });
        }
        //监控文件更新
        if (app()->isDebug()) {
            //监控应用目录,配置,第三方类库
            $paths = [app()->getAppPath(), app()->getConfigPath(),
                app()->getRootPath() . 'extend' . DIRECTORY_SEPARATOR];
            $timer = 2;
            \Workerman\Lib\Timer::add($timer, function () use ($paths, &$time) {
                foreach ($paths as $path) {
                    $dir = new \RecursiveDirectoryIterator($path);
                    $iterator = new \RecursiveIteratorIterator($dir);
                    foreach ($iterator as $file) {
                        if ($file->getExtension() != 'php') {
                            continue;
                        }
                        if ($time < $file->getMTime()) {
                            echo '[update]' . $file . "\n";
                            posix_kill(posix_getppid(), SIGUSR1);
                            $time = $file->getMTime();
                            return;
                        }
                    }
                }
            });
        }
    }
}

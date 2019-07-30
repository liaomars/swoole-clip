<?php
return [
    'master_process_name' => 'php-http-master',
    'manager_process_name' => 'php-http-manager',
    'worker_process_name' => 'php-http-worker',
    'www_user' => 'www',
    'host' => '127.0.0.1', //这里用本地地址,前端用nginx代理.所以不用开放在公网
    'port' => '9502',
    'time_zone' => 'PRC',
    'swoole_process_mode' => SWOOLE_PROCESS,//swoole的进程模式设置
    'setting' => [
        'reactor_num' => 1,
        'worker_num' => 5,
        'max_request' => 1000,
        'task_worker_num' => 2,
        'daemonize' => 0,
        // http无状态，使用1或3
        'dispatch_mode' => 3,//1:轮询模式  3:抢占模式
        'reload_async' => true,
        // 不要更改这个两个配置文件位置
        'log_file' => LOG . '/log.txt',
        'pid_file' => LOG . '/server.pid',
    ],

    // 是否内存化线上实时任务
    'open_table_tick_task' => true,
];
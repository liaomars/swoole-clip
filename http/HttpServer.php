<?php

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Thumb\Config\Config;

class HttpServer
{
    /** @var Server */
    private $_server;

    private $_config;


    public function __construct()
    {
        $this->getConfig();

        $this->_server = new \Swoole\Http\Server($this->_config['host'], $this->_config['port']);
        $this->_server->set($this->_config['setting']);
        $this->_server->on('request', [$this, 'onRequest']);
        $this->_server->on('start', [$this, 'onStart']);
        $this->_server->on('managerStart', [$this, 'onManagerStart']);
        $this->_server->on('workerStart', [$this, 'onWorkerStart']);
        $this->_server->on('task', [$this, 'onTask']);
        $this->_server->on('finish', [$this, 'onFinish']);
    }


    public function getConfig()
    {
        $this->_config = include_once __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
    }


    public function onRequest(Request $request, Response $response)
    {
        $requestUri = $request->server['request_uri'];
        if ($requestUri == '/favicon.ico') {
            return $response->end();
        }

        try {
            $get = $request->get;

            if (is_null($get)) {
                $response->end('get parameter is empty.');
            }

            $config = include ROOT . DIRECTORY_SEPARATOR . 'config.default.php';
            $config = new Config($config);

            $thumb = new \Thumb\Thumber($config, $get['file']);
            $file = $thumb->show();

            $res['code'] = 1;
            $res['msg'] = '成功';
            $res['file'] = $file;
            $response->end(json_encode($res));

        } catch (Exception $e) {
            $response->end($e->getMessage());
        }
    }


    public function onStart()
    {
        if (PHP_OS != 'Darwin')
            swoole_set_process_name($this->_config['master_process_name']);
    }

    public function onManagerStart()
    {
        if (PHP_OS != 'Darwin')
            swoole_set_process_name($this->_config['manager_process_name']);
    }


    public function onWorkerStart()
    {
        if (PHP_OS != 'Darwin')
            swoole_set_process_name($this->_config['worker_process_name']);
    }

    public function onTask(\swoole_server $serv, $taskId, $src_worker_id, mixed $data)
    {
//        $serv->finish($taskId);
    }

    public function onFinish(\swoole_server $serv, int $task_id, string $data)
    {

    }

    public function start()
    {
        $this->_server->start();
    }

    public function stop()
    {

    }
}
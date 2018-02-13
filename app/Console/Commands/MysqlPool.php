<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MysqlPool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysql:pool {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Mysql Pool';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $action = $this->argument('action');

        switch ($action) {
            case 'start':
                $this->start();
                $this->info('Mysql Pool Start Success!');
                break;
            default:
                $this->error('Wrong Params');

        }
    }

    public function start()
    {
        $serv = new \swoole_server("127.0.0.1", 9508);
        $serv->set(array(
            'worker_num' => 100,
            'task_worker_num' => 10, //MySQL连接的数量
        ));

        $serv->on('Start',array($this, 'onStart'));
        $serv->on('Receive',array($this, 'onReceive'));
        $serv->on('Task', array($this, 'onTask'));
        $serv->on('Finish', array($this, 'onFinish'));
        $serv->start();
    }

    public function onStart($server)
    {
        echo $server->worker_id . PHP_EOL;
        cli_set_process_title('mysql_pool');
    }

    public function onReceive($serv, $fd, $from_id, $data)
    {
        //taskwait就是投递一条任务，这里直接传递SQL语句了
        //然后阻塞等待SQL完成
        $result = $serv->taskwait("show tables");
        if ($result !== false) {
            list($status, $db_res) = explode(':', $result, 2);
            if ($status == 'OK') {
                //数据库操作成功了，执行业务逻辑代码，这里就自动释放掉MySQL连接的占用
                $serv->send($fd, var_export(unserialize($db_res), true) . "\n");
            } else {
                $serv->send($fd, $db_res);
            }
            return;
        } else {
            $serv->send($fd, "Error. Task timeout\n");
        }
    }

    public function onTask($serv, $task_id, $from_id, $sql)
    {
        static $link = null;
        if ($link == null) {
//            $link = mysqli_connect("47.94.11.137", "root", "108178", "gogs");
            $link = DB::connection('mysql');
            if (!$link) {
                $link = null;
                $serv->finish("ER:Mysql Connect Failed!!");
                return;
            }
        }
        $result = $link->table('users')->get();
        if (!$result) {
            $serv->finish("ER:Sql Query Failed!!");
            return;
        }
        $serv->finish("OK:" . serialize($result));
    }

    public function onFinish($serv, $data)
    {
        echo "AsyncTask Finish:Connect.PID=" . posix_getpid() . PHP_EOL;
    }

    /*
    protected function mysqlPoolStart()
    {
        $server = new \swoole_server("0.0.0.0", 9508, SWOOLE_BASE, SWOOLE_SOCK_TCP);
        $server->set(array(
            'worker_num' => 100,
            'task_worker_num' => 10, //MySQL连接的数量
	    'log_file' => base_path('storage/logs/swoole_http.log'),
        ));

        $server->on('Start', array(
            $this,
            'onStart'
        ));

        $server->on('Receive', array(
            $this,
            'onStart'
        ));

        $server->on('Task', array(
            $this,
            'onStart'
        ));

        $server->on('Finish', array(
            $this,
            'onStart'
        ));

        $server->start();
    }

    public function onStart($serv)
    {
        // 设置进程名称
        echo 'Hello ' . $serv->master_pid. PHP_EOL;
        cli_set_process_title("mysql_pool");
    }

    public function onReceive($server, $fd, $from_id, $data)
    {
        //taskwait can deliver one task
        // Then block and wait for the task to complete
        echo serialize($data) . PHP_EOL;
        $result = $server->taskwait(json_decode($data));
        if ($result !== false) {
            list($status, $db_res) = explode(':', $result, 2);
            if ($status == 'OK') {
                //数据库操作成功了，执行业务逻辑代码，这里就自动释放掉MySQL连接的占用
                $server->send($fd, var_export(unserialize($db_res), true) . "\n");
            } else {
                $server->send($fd, $db_res);
            }
            return;
        } else {
            $server->send($fd, "Error. Task timeout\n");
        }
    }

    public function onTask($server, $task_id, $from_id, $data)
    {
	echo "task $task_id" . PHP_EOL;
        static $link = null;
        if ($link == null) {
            $link = DB::connection('mysql');
            if (!$link) {
                $link = null;
                $server->finish("ER:" . mysqli_error($link));
                return;
            }
        }

        if(!$table = $data['table']) {
            $server->finish("ER:" . serialize('Be lacking of table'));
            return;
        }

        if(!$queryType = $data['type']) {
            $server->finish("ER:" . serialize('Be lacking of table'));
            return;
        }

        $query = $link->table($table);

        switch ($queryType) {
            case 'find':
                $result = $query->find($data['find']['id']);break;
            case 'first':
                $result = $query->first();break;
            case 'get':
            default:
                $result = $query->get();
        }

        if (!$result) {
            $server->finish("ER:" . mysqli_error($link));
            return;
        }

        $server->finish("OK:" . serialize($data));
    }

    public function onFinish($serv, $data)
    {
        Log::info("AsyncTask Finish:Connect.PID=" . posix_getpid() . PHP_EOL);
    }
    */
}

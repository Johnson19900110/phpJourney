<?php
/**
 * Created by Administrator.
 * Author: Administrator
 * Date: 2018/2/11
 */

namespace App\MysqlPool\Client;


trait Client
{

    protected function mysqlLink(Array $data)
    {
        if(empty($data)) {
            return 'Params is empty';
        }
        $client = new \swoole_client(SWOOLE_SOCK_TCP);
        $client->connect('0.0.0.0', 9508, 10) or die("Connect Pool Failure !");

        $client->send($data);
        //Blocking for wait result

        $data = $client->recv();

        $client->close();

        return $data;
    }
}
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff !important;
                color: #636b6f !important;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }


            pre {
                padding: 16px;
                overflow: auto;
                font-size: 14px;
                line-height: 20px;
                background-color: #f6f8fa;
                border-radius: 3px;
            }

            .content-body {
                padding: 15px 25px;
            }
        </style>
    </head>
    <body>
        <div class="content-body">
            <h2>SWOOLE实现固定包头+包体TCP协议</h2>
            <p>
                在使用socket或swoole与其他语言进行数据交互的时候，为了安全性，都会设定包头，这相当于进行身份验证。
                swoole提供了两种包头协议的实现。一种是\n\r的尾结束符，另一种就是固定包头+包体协议自动分包。
            </p>
            <h3>固定包头+包体协议自动分包</h3>
            <p>首先需要通过open_length_check打开固定包头包体协议，再用package_length_offset规定包头中第几个字节是整个包长度，package_body_offset是从第几个字节开始计算长度，比如包头为长度为120字节，第10个字节为长度值，包体长度为1000。如果长度包含包头，这里填入0，如果不包含包头，这里填入120。最后一个是package_length_type，表示长度字段的类型，涉及到网络字节序和机器字节序，都是以字节为单位的。详细信息可以去swoole官网了解。配置如下：</p>
            <pre>
        'open_length_check' => true     // 打开固定包头协议解析功能
        'package_length_offset' => 0,   // 规定了包头中第几个字节开始是长度字段
        'package_body_offset' => 0,     // length的值包含了整个包（包头+包体）
        'package_length_type' => 'N',   // 规定了长度字段的类型
            </pre>
            <p>php中的pack和unpack可以用来处理和解析网络字节序。比如：我们发的包头是</p>
            <pre>
$length=40+strlen($data);       // 40是包头的长度
$serv->send($fd, pack("N", $length));
$serv->send($fd, pack("C", $msg_type));
$serv->send($fd, pack("C", $replyCipher));
$serv->send($fd, pack("C", $compress));
$serv->send($fd, $uuid);

$serv->send($fd,$data);
            </pre>
            <p>也许有人会问，为什么长度是40加上包体长度，因为我们这里的N是无符号、网络字节序、4字节，而是无符号、1字节，然后规定uuid的长度是为33个字节，所以整个包头长度为33+4+1+1+1，那就是40了。至于各个字段的意义，那是看你们自己的定义的。</p>
            <p>包头发了，服务端肯定就有解析，这里只说明PHP对以上包头的解析。其实很简单，只是按照上面的顺序逐一用unpack解析出来就可以了。</p>
            <pre>
// 获取整个消息的长度
$msg_length = unpack("N", $data)[1];
$data = substr($data, 4);
echo "整个消息的长度:".$msg_length.PHP_EOL;

// 消息类型
$msg_type = unpack("C", $data)[1];
$data = substr($data, 1);
echo "消息类型:".$msg_type.PHP_EOL;

// 服务端响应包体是否需要加密标识  0-不需要加密  1-需要加密  保留字段
$replyCipher = unpack("C", $data)[1];
$data = substr($data, 1);
echo "响应包体是否加密标识:".$replyCipher.PHP_EOL;

// 获取包体是否需要压缩标识  0-未压缩 1-压缩  保留字段
$compress = unpack("C", $data)[1];
$data = substr($data, 1);
echo "包体是否压缩标识:".$compress.PHP_EOL;

// 请求者ID
$uuid = substr($data, 0, 33);
echo "请求者ID:". $uuid.PHP_EOL;

// 获取包体
$data = substr($data, 33);
echo $data;
            </pre>
            <p>
                因为开启了open_length_check，所以swoole会在你接收到全部的数据后，才开始对数据进行处理。不过我有一点不明白，为什么接收到整个数据后，用unpack("N", $data)[1]就可以接收到第一个传的数据？望知道的大神不吝赐教！
            </p>
        </div>
    </body>
</html>

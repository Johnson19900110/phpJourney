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

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
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

            .m-b-md {
                margin-bottom: 30px;
            }
            pre {
                padding: 16px;
                overflow: auto;
                font-size: 14px;
                line-height: 20px;
                background-color: #f6f8fa;
                border-radius: 3px;
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
            <p>打开固定包头协议解析功能</p>
            <pre>
                'open_length_check' => true
                'package_length_offset' => 3,   // 
                'package_body_offset' => 0,     // length的值包含了整个包（包头+包体）
                'package_length_type' => 'N',   // 规定了长度字段的类型
            </pre>
            <p>从第几个字节开始是长度，比如包头长度为120字节，第10个字节为长度值，这里填入9（从0开始计数）</p>
            <pre>
                'package_length_offset' => 9
            </pre>
            <p>长度字段的类型，固定包头中用一个4字节或2字节表示包体长度。类型是一个字符，详情参见php的pack函数文档 比较常用的类型为：</p>
        </div>
    </body>
</html>

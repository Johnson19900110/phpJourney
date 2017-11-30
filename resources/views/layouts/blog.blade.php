<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}?time={{ time() }}" rel="stylesheet">

    <!-- iconMoon -->
    <link rel="stylesheet" href="{{ asset('icomoon/style.css') }}?time={{ time() }}">

    <!-- Script -->
    {{--<script src="{{ asset('js/vue/vue.js') }}"></script>--}}
    {{--<script src="{{ asset('js/vue/axios.min.js') }}"></script>--}}

    <style>
        /*body,ul,ol,li,p,h1,h2,h3,h4,h5,h6,form,fieldset,table,td,img,div{margin:0;padding:0;border:0;}*/
        body{background:#fff;color:#333;font-size:12px; font-family:"SimSun","宋体","Arial Narrow";}

        ul,ol{list-style-type:none;}
        select,input,img,select{vertical-align:middle;}

        a{text-decoration:none;}

        html, body{
            height:100%;
            width: 100%;
        }

        .clearfix {
            clear: both;
        }

        #app{
            display: flex;
            width: 100%;
            height: 100%;
        }

        .leftSide{
            flex: 0 0 300px;
            width: 300px;
            padding: 25px;
            float: left;
            height: 100%;
            background: url("/images/left-bg.jpg?ver={{ time() }}}}") no-repeat;
            background-size: 100% 100%;
        }

        .leftSide .avatar-wrapper {
            margin-top: 100px;
            text-align: center;
            color: #fff;
        }

        .leftSide .avatar-wrapper .avatar {
            margin-bottom: 20px;
        }

        .leftSide .avatar-wrapper .avatar img{
            border-radius: 50%;
        }

        .leftSide .avatar-wrapper .description {
            font-size: 15px;
            color: #ccc;
            margin-top: 20px;
        }

        .leftSide ul {
            margin-top: 20px;
            font-size: 15px;
            font-weight: bold;
        }

        .leftSide ul li {
            float: left;
            padding: 0 15px;
        }

        .leftSide ul li a {
            color: #ffffff;
        }

        .leftSide ul li a:hover {
            color: #ccc;
        }

        .leftSide .icomoon {
            margin-top: 20px;
            text-align: center;
        }

        .leftSide .icomoon span {
            color: #fff;
            font-size: 20px;
        }

        .leftSide .icomoon span:hover {
            color: #ccc;
            cursor: pointer;
        }

        .rightSide{
            flex: 1;
            height: 100%;
            float: left;
            padding: 0 30px;
            overflow: auto;
        }
    </style>
    @yield('rightStyle')
</head>
<body>
<div id="app">
    @include('leftSide')

    <div class="rightSide">
        @yield('rightSide')
    </div>
</div>

<!-- Scripts -->
@section('vue-js')
    <script src="{{ asset('js/app.js') }}?time={{ time() }}"></script>
@show
</body>
</html>
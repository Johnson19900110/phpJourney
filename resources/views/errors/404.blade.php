@extends('layouts/blog')

@section('rightStyle')
    <style>
        .links-title {
            margin-top: 50px;
            border-bottom: 1px #eee solid;
            padding-bottom: 10px;
            font-size: 20px;
            height: 50px;
            line-height: 50px;
            vertical-align: top;
        }
        .post-content.markdown-body {
            margin: 20px 0;
        }
        .markdown-body {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
            line-height: 1.5;
            color: #333;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            font-size: 16px;
            line-height: 1.5;
            word-wrap: break-word;
        }
    </style>
@endsection

@section('rightSide')
    <article class="post clearfix">
        <header class="post-header">
            <h1 class="entry-title links-title">~~~~(>_<)~~~~</h1>
            <div class="clear"></div>
        </header>
        <div class="post-content markdown-body" itemprop="articleBody">
            <div class="links">
                <p> 很抱歉，页面找不到了。 {{ $message or '' }} 回 <a href="/">首页</a> </p>
            </div>
            <div class="clear"></div>
        </div>
        <footer class="post-footer" itemprop="keywords">
            <div class="clear"></div>
        </footer>
    </article>
@endsection
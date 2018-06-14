@extends('layouts/blog')

@section('rightStyle')
    <style>
        .rightSide ul li {
            padding: 50px 5px;
        }

        .rightSide .info{
            margin: 10px 0;
        }

        .rightSide .icon-calendar, .rightSide .calendar, .rightSide .icon-eye, .rightSide .views, .rightSide .icon-price-tag, .rightSide .tag {
            vertical-align: top;
            height: 20px;
            line-height: 20px;
            font-size: 14px;
            color: #CFCCCF;
        }
        .rightSide .calendar, .rightSide .views, .rightSide .tag{
            margin-left: 5px;
        }

        .tags {
            margin-top: 10px;
        }
        .title a {
            color: #000;
            text-decoration: none;
        }
        
        .title a:hover {
            text-decoration: none;
        }
        em {
            color: #EB5424;
            font-style: normal;
        }
    </style>

@endsection

@section('rightSide')
    <form action="/search">
        <div class="input-group">
            <input type="text" class="form-control h50" name="q" placeholder="关键字..." value="{{ $q ?? '' }}">
            <span class="input-group-btn"><button class="btn btn-default h50" type="submit" type="button"><span class="glyphicon glyphicon-search"></span></button></span>
        </div>
    </form>
    <ul>
        @foreach($posts as $post)
            <li style="border-bottom: 1px solid #c4c4c4;">
                <h3 class="title">
                    <a href="@if(empty($post->cnblogs_url)) {{ route('post', $post->id) }} @else {{ $post->cnblogs_url }} @endif">
                        @if(isset($post->highlight['title']))
                            @foreach($post->highlight['title'] as $item)
                                {!! $item !!}
                            @endforeach
                        @else
                            {{ $post->title }}
                        @endif
                    </a>
                </h3>
                <div class="info">
                    <span class="icon-calendar"></span><span class="calendar">{{ date('d F,Y', strtotime($post->created_at)) }}</span>
                    <span class="icon-eye"></span><span class="views">{{ $post->views }} views</span>
                </div>
                <p>
                    @if(isset($post->highlight['content']))
                        @foreach($post->highlight['content'] as $item)
                            ...{!! $item !!}...
                        @endforeach
                    @else
                        {{ empty($post->content) ? '...' : mb_substr($post->content, 0, 300) . '...' }}
                    @endif
                </p>
                <div class="more"><a href="@if(empty($post->cnblogs_url)) {{ route('post', $post->id) }} @else {{ $post->cnblogs_url }} @endif">[阅读更多<span class="icon-arrow-right"></span>]</a></div>
                <div class="tags">
                    @foreach($post->tags as $tag)
                        <span class="icon-price-tag"></span><a href="{{ route('tags', $tag->tags_flag) }}" class="tag">{{ $tag->tags_name }}</a>
                    @endforeach
                </div>
            </li>
        @endforeach
    </ul>
    {!! $posts->links() !!}
@endsection
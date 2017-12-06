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
    </style>

@endsection

@section('rightSide')
    <ul>

        @foreach($posts->data as $post)
            <li style="border-bottom: 1px solid #c4c4c4;">
                <h3 class="title"><a href="{{ route('post', $post->id) }}">{{ $post->title }}</a></h3>
                <div class="info">
                    <span class="icon-calendar"></span><span class="calendar">{{ date('d F,Y', strtotime($post->created_at)) }}</span>
                    <span class="icon-eye"></span><span class="views">{{ $post->views }} views</span>
                </div>
                <p>{{ empty($post->content) ? '...' : mb_substr(strip_tags($post->content), 0, 300) . '...' }}</p>
                <div class="more"><a href="{{ route('post', $post->id) }}">[阅读更多<span class="icon-arrow-right"></span>]</a></div>
                <div class="tags">
                    @foreach($post->tags as $tag)
                        <span class="icon-price-tag"></span><a href="{{ route('tags', $tag->tags_flag) }}" class="tag">{{ $tag->tags_name }}</a>
                    @endforeach
                </div>
            </li>
        @endforeach
    </ul>
    {!! $posts->links !!}
@endsection
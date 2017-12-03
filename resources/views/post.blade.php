@extends('layouts/blog')

@section('rightStyle')
    <style>
        .post_body {
            padding: 50px 20px;
        }
        .rightSide ul li {
            padding: 50px 5px;
            border-bottom: 1px solid red;
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
    </style>
@endsection

@section('rightSide')
    <div class="post_body">
        <h3>{{ $post->title or '' }}</h3>
        <div class="info">
            <span class="icon-calendar"></span><span class="calendar">{{ isset($post->created_at) ? date('d F,Y', strtotime($post->created_at)) : '' }}</span>
            <span class="icon-eye"></span><span class="views">{{ $post->views or 0}} views</span>
        </div>
        {!! $post->content or '' !!}
        <div class="tags">
            @if(isset($post->tags))
                @foreach($post->tags as $tag)
                    <span class="icon-price-tag"></span><a href="{{ route('tags', $tag->tags_flag) }}" class="tag">{{ $tag->tags_name }}</a>
                @endforeach
            @endif
        </div>
        <div>
            <comment post="{{ $post->id or 0 }}"></comment>
        </div>
    </div>
@endsection
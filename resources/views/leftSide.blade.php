<aside class="leftSide">
    <div class="avatar-wrapper">
        <div class="avatar">
            <img src="{{ asset('images/avatar.jpg') }}" alt="" width="125" height="100">
        </div>
        <div class="nickname">
            <h1>Johnson-枫</h1>
        </div>
        <div class="description">
            <p>Somebody has to win, So why not be me!</p>
        </div>
    </div>

    <ul>
        <li><a href="{{ config('app.url') }}">首页</a></li>
        @include('widgets.navigation')
    </ul>

    <div class="clearfix"></div>

    <div class="icomoon">
        <a href="https://github.com/Johnson19900110" target="_blank"><span class="icon-github"></span></a>

    </div>
</aside>
@extends('layouts/blog')

@section('rightStyle')
<style>
    .rightSide ul li {
        padding: 50px 5px;
        border-bottom: 1px solid red;
    }

    .rightSide .info{
        margin: 10px 0;
    }

    .rightSide .icon-calendar, .rightSide .calendar, .rightSide .icon-eye, .rightSide .views {
        vertical-align: top;
        height: 20px;
        line-height: 20px;
        font-size: 14px;
        color: #CFCCCF;
    }
    .rightSide .calendar, .rightSide .views{
        margin-left: 5px;
    }
</style>

@endsection

@section('rightSide')
    <ul>
        <li>
            <h3>标题111</h3>
            <div class="info">
                <span class="icon-calendar"></span><span class="calendar">{{ date('d F,Y') }}</span>
                <span class="icon-eye"></span><span class="views">{{ mt_rand(10000, 99999) }} views</span>
            </div>
            <p>阿萨德发加啊考虑时间的看法阿里的司法局阿拉斯加放流口水的进风口了嘉善路的法拉盛剪短发了静安寺了的解放路静安寺的了房间里刷卡定积分了跨世纪的雷锋就阿萨德发加啊考虑时间的看法阿里的司法局阿拉斯加放流口水的进风口了嘉善路的法拉盛剪短发了静安寺了的解放路静安寺的了房间里刷卡定积分了跨世纪的雷锋就阿萨德发加啊考虑时间的看法阿里的司法局阿拉斯加放流口水的进风口了嘉善路的法拉盛剪短发了静安寺了的解放路静安寺的了房间里刷卡定积分了跨世纪的雷锋就阿萨德发加啊考虑时间的看法阿里的司法局阿拉斯加放流口水的进风口了嘉善路的法拉盛剪短发了静安寺了的解放路静安寺的了房间里刷卡定积分了跨世纪的雷锋就</p>
            <div class="more">[阅读更多->]</div>
        </li>
        <li>
            <h3>标题111</h3>
            <div class="info">{{ date('Y-m-d H:i:s') }}</div>
            <p>阿萨德发加啊考虑时间的看法阿里的司法局阿拉斯加放流口水的进风口了嘉善路的法拉盛剪短发了静安寺了的解放路静安寺的了房间里刷卡定积分了跨世纪的雷锋就</p>
            <div class="more">[阅读更多->]</div>
        </li>
        <li>
            <h3>标题111</h3>
            <div class="info">{{ date('Y-m-d H:i:s') }}</div>
            <p>阿萨德发加啊考虑时间的看法阿里的司法局阿拉斯加放流口水的进风口了嘉善路的法拉盛剪短发了静安寺了的解放路静安寺的了房间里刷卡定积分了跨世纪的雷锋就</p>
            <div class="more">[阅读更多->]</div>
        </li>
    </ul>
@endsection
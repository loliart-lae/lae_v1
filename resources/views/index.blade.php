@extends('layouts.app')

@section('title', '今日')

@section('content')
    <div class="mdui-panel-item mdui-panel-item-open">
        <div class="mdui-panel-item-header">迁移通知</div>
        <div class="mdui-panel-item-body">
            <p>Light App Engine 正在迁移到新的平台。</p>
            您的用户数据将可能会做出如下改变:
            <ol>
                <li>项目将改为团队，原有项目的积分将会被退回到项目所有者的用户积分中。</li>
                <li>用户将不会被删除，积分会被转换为新平台的“余额”，并反向换算。</li>
                <li>由于两者平台之间改动较大，实例数据将不能互通。请提前备份好实例的数据。</li>
            </ol>

        </div>
    </div>
    @auth
        <div class="mdui-typo mdui-p-t-4">
            <div class="mdui-text-center mdui-typo-display-1-opacity">
                <span class="material-icons">north_west</span>打开抽屉以继续您的工作。
            </div>

            <div class="mdui-text-center mdui-typo-caption-opacity">
                我们意识到了LAE目前存在的问题，并且我们正在编码新的平台。
                <br />
                这需要一段时间给我们打磨，以给大家营造出最好的体验。
            </div>

        </div>

        <script>
            document.title = window.util.text.c('接下来想要干什么？')
        </script>
    @endauth

@endsection

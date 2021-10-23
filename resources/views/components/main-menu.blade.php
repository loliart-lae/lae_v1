<script src="https://kit.fontawesome.com/4448cbb8f6.js" crossorigin="anonymous"></script>

<div>
    <div class="mdui-list" id="main-list">
        @guest
            <a class="mdui-list-item mdui-ripple umami--click--main-link" href="{{ route('index') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">home</i>
                <div class="mdui-list-item-content">{{ config('app.name') }}</div>
            </a>
            <a class="mdui-list-item mdui-rippl umami--click--gust-login" href="{{ route('login') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">assignment_ind</i>
                <div class="mdui-list-item-content">登录</div>
            </a>
            <a class="mdui-list-item mdui-ripple umami--click--why-begin" href="{{ route('why_begin') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons">volunteer_activism</span>
                <div class="mdui-list-item-content">我们的初心</div>
            </a>
        @else
            <a class="mdui-list-item mdui-ripple umami--click--main-link" href="{{ route('main') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">home</i>
                <div class="mdui-list-item-content">{{ config('app.name') }}</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--user-index" href="{{ route('user.index') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">account_circle</i>
                <div class="mdui-list-item-content"><small>
                        {{ Auth::user()->name }} / <span id="userBalance"
                            style="display: contents;">{{ Auth::user()->balance }}</span></small></div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--project" href="{{ route('projects.index') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons">emoji_objects</span>
                <div class="mdui-list-item-content">项目管理</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--ae" href="{{ route('lxd.index') }}">
                <i class="mdui-list-item-icon mdui-icon fa-brands fa-linux"></i>
                <div class="mdui-list-item-content">应用容器</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--shared-windows" href="{{ route('remote_desktop.index') }}">
                <i class="mdui-list-item-icon mdui-icon fa-brands fa-windows"></i>
                <div class="mdui-list-item-content">共享的 Windows</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--tunnel" href="{{ route('tunnels.index') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons">dns</span>
                <div class="mdui-list-item-content">穿透隧道</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--tunnel" href="{{ route('fastVisit.index') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">call_missed_outgoing</i>
                <div class="mdui-list-item-content">快捷访问</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--easypanel" href="{{ route('easyPanel.index') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons">view_carousel</span>
                <div class="mdui-list-item-content">EasyPanel 站点</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--staticPage" href="{{ route('staticPage.index') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">web</i>
                <div class="mdui-list-item-content">静态站点</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--document" href="{{ route('documents.index') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">art_track</i>
                <div class="mdui-list-item-content">文档中心</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--forum" target="_blank" href="https://f.lightart.top">
                <span class="mdui-list-item-icon mdui-icon material-icons">question_answer</span>
                <div class="mdui-list-item-content">社区论坛</div>
            </a>

            <a onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                class="mdui-list-item mdui-ripple umami--click--logout" target="_blank" href="https://f.lightart.top">
                <i class="mdui-list-item-icon mdui-icon material-icons">exit_to_app</i>
                <div class="mdui-list-item-content">退出登录</div>
            </a>

            <form style="display: none" id="logout-form" action="{{ route('logout') }}" method="POST"
                class="d-none">
                @csrf
            </form>
        @endguest
    </div>
</div>
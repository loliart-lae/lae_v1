<div>
    <div class="mdui-list" id="main-list">
        @guest
            <a class="mdui-list-item mdui-ripple umami--click--main-link" href="{{ route('index') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">home</span>
                <div class="mdui-list-item-content">{{ config('app.name') }}</div>
            </a>
            <a class="mdui-list-item mdui-rippl umami--click--gust-login" href="{{ route('login') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">login</span>
                <div class="mdui-list-item-content">登录</div>
            </a>
            <a class="mdui-list-item mdui-ripple umami--click--why-begin" href="{{ route('why_begin') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">volunteer_activism</span>
                <div class="mdui-list-item-content">我们的初心</div>
            </a>
        @else
            <a class="mdui-list-item mdui-ripple umami--click--main-link" href="{{ route('main') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">stream</span>
                <div class="mdui-list-item-content">{{ config('app.name') }}</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--user-index" href="{{ route('user.index') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">account_circle</span>
                <div class="mdui-list-item-content"><small>
                        {{ Auth::user()->name }} / <span id="userBalance"
                            style="display: contents;">{{ Auth::user()->balance }}</span></small></div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--project" href="{{ route('projects.index') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">groups</span>
                <div class="mdui-list-item-content">项目管理</div>
            </a>

            {{-- <a class="mdui-list-item mdui-ripple umami--click--ae" href="{{ route('workspace.index') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">
                    widgets
                </span>
                <div class="mdui-list-item-content">应用容器</div>
            </a> --}}

            <a class="mdui-list-item mdui-ripple umami--click--ae" href="{{ route('lxd.index') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">
                    workspaces
                </span>
                <div class="mdui-list-item-content">工作空间</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--shared-windows"
                href="{{ route('remote_desktop.index') }}">
                <span class="mdui-list-item-icon mdui-icons material-icons-outlined material-icons-outlined-outlined">
                    desktop_windows
                </span>
                <div class="mdui-list-item-content">共享的 Windows</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--tunnel" href="{{ route('tunnels.index') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">swap_horiz</span>
                <div class="mdui-list-item-content">穿透隧道</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--tunnel" href="{{ route('fastVisit.index') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">call_missed_outgoing</span>
                <div class="mdui-list-item-content">快捷访问</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--easypanel" href="{{ route('easyPanel.index') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">dashboard</span>
                <div class="mdui-list-item-content">EasyPanel 站点</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--staticPage" href="{{ route('staticPage.index') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">language</span>
                <div class="mdui-list-item-content">静态站点</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--document" href="{{ route('documents.index') }}">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">description</span>
                <div class="mdui-list-item-content">文档中心</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--forum" target="_blank" href="https://f.lightart.top">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">question_answer</span>
                <div class="mdui-list-item-content">社区论坛</div>
            </a>

            <a onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                class="mdui-list-item mdui-ripple umami--click--logout" target="_blank" href="https://f.lightart.top">
                <span class="mdui-list-item-icon mdui-icon material-icons-outlined">
                    logout
                </span>
                <div class="mdui-list-item-content">退出登录</div>
            </a>

            <form style="display: none" id="logout-form" action="{{ route('logout') }}" method="POST"
                class="d-none">
                @csrf
            </form>
        @endguest
    </div>
</div>

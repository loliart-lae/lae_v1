<div>
    <div class="mdui-list" id="main-list">
        @guest
            <a class="mdui-list-item mdui-ripple umami--click--main-link" href="{{ route('index') }}">
                <div class="mdui-list-item-content">{{ config('app.name') }}</div>
            </a>
            <a class="mdui-list-item mdui-rippl umami--click--gust-login" href="{{ route('login') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">assignment_ind</i>
                <div class="mdui-list-item-content">登录</div>
            </a>
            <a class="mdui-list-item mdui-ripple umami--click--why-begin" href="{{ route('why_begin') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">directions_run</i>
                <div class="mdui-list-item-content">我们的初心</div>
            </a>
        @else
            <a class="mdui-list-item mdui-ripple umami--click--main-link" href="{{ route('main') }}">
                <div class="mdui-list-item-content">{{ config('app.name') }}</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--user-index" href="{{ route('user.index') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">account_circle</i>
                <div class="mdui-list-item-content"><small>
                        {{ Auth::user()->name }} / <span id="userBalance"
                            style="display: contents;">{{ Auth::user()->balance }}</span></small></div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--project" href="{{ route('projects.index') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">home</i>
                <div class="mdui-list-item-content">项目管理</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--ae" href="{{ route('lxd.index') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">home</i>
                <div class="mdui-list-item-content">应用容器</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--shared-windows"
                href="{{ route('remote_desktop.index') }}">
                <svg t="1634993988130" class="mdui-list-item-icon mdui-icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2694" width="18" height="18">
                    <path d="M523.8 191.4v288.9h382V128.1zM523.8 833.6l382 62.2v-352h-382zM120.1 480.2H443V201.9l-322.9 53.5zM120.1 770.6L443 823.2V543.8H120.1z" p-id="2695"></path>
                </svg>
                <div class="mdui-list-item-content">共享的 Windows</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--tunnel" href="{{ route('tunnels.index') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">home</i>
                <div class="mdui-list-item-content">穿透隧道</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--tunnel" href="{{ route('fastVisit.index') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">call_missed_outgoing</i>
                <div class="mdui-list-item-content">快捷访问</div>
            </a>

            <a class="mdui-list-item mdui-ripple umami--click--easypanel" href="{{ route('easyPanel.index') }}">
                <i class="mdui-list-item-icon mdui-icon material-icons">home</i>
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
                <i class="mdui-list-item-icon mdui-icon material-icons">link</i>
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


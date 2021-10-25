 <ul class="mdui-menu" id="app-menu" style="border-radius: 10px">
     <li class="mdui-menu-item">
         <a href="{{ route('user.index') }}" class="mdui-ripple">
             <i class="mdui-menu-item-icon mdui-icon material-icons-outlined">person</i>
             {{ Auth::user()->name }}</a>
     </li>
     <li class="mdui-menu-item">
         <a href="{{ route('billing.index') }}" class="mdui-ripple">
             <i class="mdui-menu-item-icon mdui-icon material-icons-outlined">account_balance_wallet</i>
             {{ Auth::user()->balance }}</a>
     </li>
     <li class="mdui-menu-item">
         <a href="{{ route('user.messages') }}" class="mdui-ripple umami--click--show-messages">
             <i class="mdui-menu-item-icon mdui-icon material-icons-outlined">timeline</i>
             历史消息</a>
     </li>
     <li class="mdui-menu-item">
         <a href="{{ route('user.balanceLog') }}" class="mdui-ripple umami--click--show-balanceLog">
             <i class="mdui-menu-item-icon mdui-icon material-icons-outlined">history</i>积分历史
         </a>
     </li>

     <li class="mdui-divider"></li>
     <li class="mdui-menu-item">
         <a onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="mdui-ripple">
             <i class="mdui-menu-item-icon mdui-icon material-icons-outlined">logout</i>
             退出登录</a>
     </li>
 </ul>

 <ul class="mdui-menu @if (!Agent::isMobile()) mdui-menu-cascade @endif" id="app-menu" style="border-radius: 10px">
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
             <i class="mdui-menu-item-icon mdui-icon material-icons-outlined">history</i>
             积分历史
         </a>
     </li>

     @if (!Agent::isMobile())

         @if (count($sponsors) > 0)
             <li class="mdui-divider"></li>
             <li class="mdui-menu-item">
                 <a href="#" class="mdui-ripple">
                     <i class="mdui-menu-item-icon mdui-icon material-icons-outlined">looks</i>
                     共建者与赞助商
                     <span class="mdui-menu-item-more"></span>
                 </a>

                 @foreach ($sponsors as $sponsor)
                     <ul class="mdui-menu mdui-menu-cascade">
                         <li class="mdui-menu-item">
                             <a href="#" class="mdui-ripple">
                                 <i class="mdui-menu-item-icon"></i>{{ $sponsor->name }}
                             </a>
                             @if (count($sponsor->SponsorAds) > 0)
                                 <ul class="mdui-menu mdui-menu-cascade">
                                     @foreach ($sponsor->SponsorAds as $ad)
                                         @include('include._sponsorAds', ['ad' => $ad])
                                     @endforeach
                                 </ul>
                             @endif
                         </li>
                     </ul>
                 @endforeach
             </li>

         @endif
     @endif
     <li class="mdui-divider"></li>
     <li class="mdui-menu-item">
         <a onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="mdui-ripple">
             <i class="mdui-menu-item-icon mdui-icon material-icons-outlined">logout</i>
             退出登录</a>
     </li>
 </ul>

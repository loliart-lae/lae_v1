<li class="mdui-menu-item">
    <a href="javascript:;" class="mdui-ripple">
        <i class="mdui-menu-item-icon"></i>{{ $ad->name }}
    </a>

    @if ($ad->sponsors)
        @if (count($ad->sponsors) > 0)
            <ul class="mdui-menu mdui-menu-cascade">
                @foreach ($ad->sponsors as $childAd)
                    @include('include._sponsorAds', ['ad' => $childAd])
                @endforeach
            </ul>
        @endif
    @endif
</li>

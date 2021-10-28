@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <div>

        <div class="mdui-row mdui-typo">
            <div class="mdui-col-xs-12 mdui-col-sm-5">
                <img class="mdui-img-circle mdui-hoverable animate__bounceIn mdui-center"
                    src="{{ config('app.gravatar_url') }}/{{ md5(strtolower($user->email)) }}?s=192">

            </div>

            <div class="mdui-col-xs-12 mdui-col-sm-7">
                <div class="mdui-typo-display-1">{{ $user->name }}</div>

                <p>积分: <span class="userBalance">{{ $user->balance }}</span>，<a href="{{ route('billing.index') }}"
                        class="umami--click--goto-charge">充值</a></p>
                <p>字段: <a href="{{ route('field.index') }}" class="umami--click--goto-field">显示字段</a>
                </p>

                <form method="POST" action="{{ route('user.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mdui-textfield">
                        <label class="mdui-textfield-label">签名</label>
                        <input class="mdui-textfield-input" type="text" name="bio" value="{{ $user->bio }}" />
                    </div>

                    <div class="mdui-textfield">
                        <label class="mdui-textfield-label">网站</label>
                        <input class="mdui-textfield-input" type="url" name="website" value="{{ $user->website }}"
                            placeholder="http(s)://" />
                    </div>

                    <div class="mdui-m-t-2 mdui-m-b-2">
                        <label class="mdui-checkbox">
                            <input type="checkbox" value="1" name="wp_index" @if ($user->wp_index) checked @endif />
                            <i class="mdui-checkbox-icon"></i>
                            索引站点(仅WordPress)
                        </label>
                    </div>


                    <button type="submit"
                        class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-m-r-1 umami--click--update-profile">修改</button>
                    <span onclick="updateToken()"
                        class="mdui-btn mdui-color-theme-accent mdui-ripple umami--click--update-token">更新访问密钥</span>
                </form>
            </div>
        </div>
    </div>

    <script>
        new ClipboardJS('.can_copy')

        function updateToken() {
            mdui.confirm('访问密钥更新后，原先的访问密钥将不可用。',
                function() {
                    $.ajax({
                        type: 'PUT',
                        url: "{{ route('user.generateToken') }}",
                        success: function(data) {
                            mdui.snackbar({
                                message: '访问密钥 已更新 <span class></span>',
                                buttonText: '复制',
                                position: 'right-bottom',
                                onButtonClick: function() {
                                    $('body').append(
                                        `<span style="display: none" class="can_copy" data-clipboard-text="${data.api_token}"></span>`
                                    )
                                    $('.can_copy').click()
                                    $('.can_copy').remove()
                                }
                            });
                        }
                    })
                }
            );
        }
    </script>

@endsection

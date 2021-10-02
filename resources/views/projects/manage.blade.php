@extends('layouts.app')

@section('title', $project_info->name)

@section('content')
    <div class="mdui-typo-display-2">有关 {{ $project_info->name }}</div>
    <div class="mdui-typo-body-1-opacity">{{ $project_info->description }}</div>

    <br />
    <br />

    项目所有者: {{ $project_info->user->name }}
    <br />
    项目积分:{{ $project_info->balance }}</span>
    @if ($project_info->balance < 100)
        <span mdui-tooltip="{content: '点击显示详细信息'}" onclick="balance_low()">&nbsp;!项目积分过少!</span>
    @endif
    <br />
    <br />

    <a style="margin: 3px" class="mdui-btn mdui-color-theme-accent mdui-ripple"
        mdui-dialog="{target: '#invite-dialog'}">邀请新成员到项目</a>
    <a style="margin: 3px" class="mdui-btn mdui-color-theme-accent mdui-ripple"
        mdui-dialog="{target: '#charge-dialog'}">汇款积分至项目</a>
    <a style="margin: 3px" href="{{ route('invite.index', $project_info->id) }}"
        class="mdui-btn mdui-color-theme-accent mdui-ripple">邀请状态</a>
    <a style="margin: 3px" href="{{ route('storage.index', $project_info->id) }}"
        class="mdui-btn mdui-color-theme-accent mdui-ripple">存储</a>

    @if ($project_info->user_id != Auth::id())
        <form style="display: inline;" method="POST" action="{{ route('projects.leave', $project_info->id) }}">
            @csrf
            <button style="margin: 3px" onclick="if(!confirm('离开后，将不会退回任何资金，也无法撤销该操作。')) return false"
                class="mdui-btn mdui-color-theme-accent mdui-ripple">离开</button>
        </form>
    @else
        <a style="margin: 3px" href="{{ route('projects.edit', $project_info->id) }}"
            class="mdui-btn mdui-color-theme-accent mdui-ripple">修改</a>
        <a style="margin: 3px" href="{{ route('projects.destroy', $project_info->id) }}"
            class="mdui-btn mdui-color-theme-accent mdui-ripple" mdui-dialog="{target: '#destroy-dialog'}">解散</a>
    @endif




    <h1 class="mdui-text-color-theme">项目人员管理</h1>

    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>邮箱</th>
                    <th>操作</th>
                    <th>加入时间</th>
                </tr>
            </thead>
            <tbody class="mdui-typo">
                @php($i = 1)
                @foreach ($members as $member)
                    <tr>
                        <td nowrap="nowrap">{{ $i++ }}</td>
                        <td nowrap="nowrap">{{ $member->user->name }}</td>
                        <td nowrap="nowrap">{{ $member->user->email }}</td>

                        @if (Auth::id() == $project_info->user_id)
                            @if (Auth::id() != $member->user->id)
                                <td class="mdui-text-color-blue" mdui-dialog="{target: '#kick_dialog_{{ $i }}'}"
                                    onclick="$('.selected_user_name').text('{{ $member->user->name }}')">请出</td>

                                <div class="mdui-dialog" id="kick_dialog_{{ $i }}">
                                    <div class="mdui-dialog-title">请出 {{ $member->user->name }}</div>
                                    <div class="mdui-dialog-content">
                                        请出后，{{ $member->user->name }} 不会收到退款，他也会失去对 {{ $project_info->name }} 的所有控制权。
                                        <form id="f_kick_{{ $i }}" method="POST"
                                            action="{{ route('members.destroy', [$project_info->id, $member->user->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>

                                    <div class="mdui-dialog-actions">
                                        <button class="mdui-btn mdui-ripple" mdui-dialog-close>取消</button>
                                        <button onclick="$('#f_kick_{{ $i }}').submit()"
                                            class="mdui-btn mdui-ripple">请出</button>
                                    </div>
                                </div>
                            @else
                                <td nowrap="nowrap">您自己</td>
                            @endif
                        @else
                            <td nowrap="nowrap">无权操作</td>
                        @endif
                        <td nowrap="nowrap">{{ $member->created_at }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mdui-dialog" id="invite-dialog">
        <div class="mdui-dialog-title">邀请<span class="invite_call"></span>至<span class="selected_project_name"></span>
        </div>
        <form method="POST" action="{{ route('invite.store', $project_info->id) }}">
            <div class="mdui-dialog-content">请填写对方邮箱，邀请信将发送到站内信中。<br />

                @csrf
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">Email</label>
                    <input class="mdui-textfield-input" type="email" name="email" required />
                </div>
            </div>
            <div class="mdui-dialog-actions">
                <button class="mdui-btn mdui-ripple" mdui-dialog-close>取消</button>
                <button type="submit" class="mdui-btn mdui-ripple">发送邀请</button>
            </div>
        </form>
    </div>

    <div class="mdui-dialog" id="charge-dialog">
        <div class="mdui-dialog-title">汇款积分至项目</div>

        <form method="POST" action="{{ route('projects.charge', $project_info->id) }}">
            <div class="mdui-dialog-content">
                <p>注意：你无法将你的全部积分汇款至项目。你拥有 {{ Auth::user()->balance }} 积分，但只能汇入 {{ Auth::user()->balance - 1 }} 积分。</p>

                @csrf
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">输入积分</label>
                    <input class="mdui-textfield-input" type="text" name="value" required />
                </div>
            </div>
            <div class="mdui-dialog-actions">
                <button class="mdui-btn mdui-ripple" mdui-dialog-close>取消</button>
                <button type="submit" class="mdui-btn mdui-ripple">充值</button>
            </div>
        </form>
    </div>

    <div class="mdui-dialog" id="destroy-dialog">
        <div class="mdui-dialog-title">解散</div>
        <form method="POST" action="{{ route('projects.destroy', $project_info->id) }}">
            <div class="mdui-dialog-content">请不要在还有剩余资金的情况下解散项目，因为资金无法退回。<br />
                @csrf
                @method('DELETE')
            </div>
            <div class="mdui-dialog-actions">
                <button class="mdui-btn mdui-ripple" mdui-dialog-close>取消</button>
                <button type="submit" class="mdui-btn mdui-ripple">确认解散！</button>
            </div>
        </form>
    </div>

    <div class="mdui-dialog" id="leave-dialog">
        <div class="mdui-dialog-title">离开</div>
        <div class="mdui-dialog-content">离开后，你将会失去对这个项目的访问权，除非您再次被邀请，并且在该项目中汇款的积分也不会退回。</div>
        <div class="mdui-dialog-actions">
            <button class="mdui-btn mdui-ripple">取消</button>
            <button class="mdui-btn mdui-ripple">离开</button>
        </div>
    </div>



@endsection


@section('script')
    <script>
        function balance_low() {
            mdui.snackbar({
                message: '注意！<br />积分过少会导致您的数据处于危险状态，如果该项目的积分无法支撑服务扣费，那么您的服务将会被逐一删除，并且不会保留任何数据。如果您想长久使用，应保持积分处于充足状态。',
                position: 'right-bottom'
            });

        }
        @if ($project_info->balance < 100)
            balance_low()
        @endif
    </script>
@endsection

<div class="mdui-row mdui-p-y-2 mdui-p-l-1">
    <span class="mdui-typo-headline">选择项目</span>
</div>

<div class="mdui-table-fluid">
    <table class="mdui-table mdui-table-hoverable">
        <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>项目积分</th>
                <th>选择</th>
            </tr>
        </thead>
        <tbody>
            @php($i = 1)
            @foreach ($projects as $project)
                <tr>
                    <td nowrap="nowrap">{{ $i++ }}</td>
                    <td nowrap="nowrap">{{ $project->project->name }}</td>
                    <td nowrap="nowrap">{{ $project->project->balance }}</td>

                    <td>
                        <label class="mdui-radio">
                            <input type="radio" value="{{ $project->project->id }}" name="project_id"
                                @if ($i == 2) checked @endif required />
                            <i class="mdui-radio-icon"></i>

                        </label>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

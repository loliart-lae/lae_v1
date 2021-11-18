<div id="top-tab" class="mdui-tab mdui-tab-scrollable mdui-color-theme animate_height" mdui-tab>
    @foreach ($projects as $project)
        <a href="#proj-index-show-{{ $project->project_id }}" class="mdui-ripple">{{ $project->project->name }}</a>
    @endforeach
</div>


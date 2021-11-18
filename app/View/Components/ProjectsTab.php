<?php

namespace App\View\Components;

use App\Models\ProjectMember;
use Illuminate\View\Component;

class ProjectsTab extends Component
{
    public $projects;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->projects = ProjectMember::where('user_id', auth()->id())->with('project')->get();

        return view('components.projects-tab');
    }
}

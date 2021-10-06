<?php

namespace App\View\Components;

use App\Models\ProjectMember;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class ChooseProjectForm extends Component
{
    public $projects;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $member = new ProjectMember;
        $projects = $member->where('user_id', Auth::id())->with('project')->get();
        $this->projects = $projects;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.choose-project-form');
    }
}

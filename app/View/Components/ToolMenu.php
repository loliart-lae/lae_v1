<?php

namespace App\View\Components;

use App\Models\Sponsor;
use Illuminate\View\Component;

class ToolMenu extends Component
{
    public $sponsors;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sponsors = Sponsor::whereNull('sponsor_id')
        ->with('SponsorAds')
        ->get();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tool-menu');
    }
}

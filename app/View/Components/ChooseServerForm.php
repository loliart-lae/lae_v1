<?php

namespace App\View\Components;

use App\Models\Server;
use Illuminate\View\Component;

class ChooseServerForm extends Component
{

    public $type;
    public $servers;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $server = new Server();
        $servers = $server->where('free_disk', '>', '5')->where('free_mem', '>', '1024')->where('type', $type)->get();
        $this->servers = $servers;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.choose-server-form');
    }
}

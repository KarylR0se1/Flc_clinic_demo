<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function render()
    {
        return view('components.sidebar');
    }
}

<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BarChart extends Component
{
    public string $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function render()
    {
        return view('components.bar-chart');
    }
}

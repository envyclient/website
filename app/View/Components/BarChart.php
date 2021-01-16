<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BarChart extends Component
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function render()
    {
        return view('components.bar-chart');
    }
}

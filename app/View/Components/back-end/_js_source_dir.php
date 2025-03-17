<?php

namespace App\View\Components\back-end;

use Illuminate\View\Component;

class _js_source_dir extends Component
{
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
        return view('components.back-end._js_source_dir');
    }
}

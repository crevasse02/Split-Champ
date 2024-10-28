<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class modalCenter extends Component
{
    public $id; // Modal ID
    public $title; // Modal title
    public $index;

    public function __construct($id, $title, $index)
    {
        $this->id = $id;
        $this->title = $title;
        $this->index = $index;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal-center');
    }
}

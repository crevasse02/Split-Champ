<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class modalDetail extends Component
{
    // public $id;
    // public $title; 
    // public $variantList; 
    // public $experimentData; 

    public function __construct()
    {
        // $this->id = $id;
        // $this->title = $title;
        // $this->variantList = $variantList;
        // $this->experimentData = $experimentData;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal-detail');
    }
}

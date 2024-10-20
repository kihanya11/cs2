<?php

namespace App\Livewire;

use Livewire\Component;

class MapComponent extends Component
{
    public function render()
    {
        return view('map')
        ->layout('layouts.guest');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;

class ToggleDeskripsi extends Component
{
    public $isDeskripsiVisible = false;
    public $deskripsi = '';
    public function render()
    {
        return view('livewire.toggle-deskripsi');
    }
}

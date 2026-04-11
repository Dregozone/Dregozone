<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Image Converter')]
class ImageConverter extends Component
{
    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.image-converter');
    }
}

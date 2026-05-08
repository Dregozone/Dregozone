<?php

namespace App\Livewire\Admin;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Image Converter')]
class ImageConverter extends Component
{
    public function render(): View
    {
        return view('livewire.admin.image-converter');
    }
}

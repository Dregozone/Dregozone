<?php

namespace App\Livewire;

use App\Models\Tool;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.main')]
#[Title('Tools')]
class Tools extends Component
{
    public function render()
    {
        $tools = Tool::ordered()->with('uploadedImage')->get();

        return view('livewire.tools', [
            'tools' => $tools,
        ]);
    }
}

<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.main')]
#[Title('Privacy Policy')]
class PrivacyPolicy extends Component
{
    public function render()
    {
        return view('livewire.privacy-policy');
    }
}

<?php

namespace App\Livewire;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app.blank')]
class Contact extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';
    public $type = 'general';
    public $budget = '';
    public $timeline = '';
    public $projectType = '';

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|min:5|max:255',
        'message' => 'required|min:10',
        'type' => 'required|in:work_request,general,partnership',
    ];

    public function submit()
    {
        $this->validate();

        $metadata = [];
        
        if ($this->type === 'work_request') {
            $metadata = [
                'budget' => $this->budget,
                'timeline' => $this->timeline,
                'projectType' => $this->projectType,
            ];
        }

        ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'type' => $this->type,
            'metadata' => $metadata,
        ]);

        $this->reset(['name', 'email', 'subject', 'message', 'budget', 'timeline', 'projectType']);
        $this->type = 'general';

        session()->flash('message', 'Thank you for your message! I\'ll get back to you soon.');
    }

    public function render()
    {
        return view('livewire.contact');
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Contact Submissions')]
class ContactMessageList extends Component
{
    use WithPagination;

    public $search = '';

    public $status = '';

    public $type = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateStatus(int $messageId, string $newStatus): void
    {
        $message = ContactMessage::findOrFail($messageId);

        match ($newStatus) {
            'replied' => $message->markAsReplied(),
            'ignored' => $message->markAsIgnored(),
            'actioned' => $message->markAsActioned(),
            default => null,
        };

        session()->flash('message', 'Status updated to "' . $newStatus . '".');
    }

    public function render()
    {
        $query = ContactMessage::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('subject', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.admin.contact-message-list', [
            'messages' => $messages,
        ]);
    }
}

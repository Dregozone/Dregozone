<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'type',
        'status',
        'metadata',
        'status_changed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'status_changed_at' => 'datetime',
    ];

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeUnread($query)
    {
        return $query->whereIn('status', ['new', 'read']);
    }

    public function markAsRead(): void
    {
        $this->update(['status' => 'read']);
    }

    public function markAsReplied(): void
    {
        $this->update([
            'status' => 'replied',
            'status_changed_at' => now(),
        ]);
    }

    public function markAsIgnored(): void
    {
        $this->update([
            'status' => 'ignored',
            'status_changed_at' => now(),
        ]);
    }

    public function markAsActioned(): void
    {
        $this->update([
            'status' => 'actioned',
            'status_changed_at' => now(),
        ]);
    }
}

<?php

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeMessage(array $attrs = []): ContactMessage
{
    return ContactMessage::create(array_merge([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'subject' => 'Test subject',
        'message' => 'Test message body',
        'type' => 'general',
        'status' => 'new',
    ], $attrs));
}

test('markAsRead sets status to read', function () {
    $message = makeMessage();
    $message->markAsRead();

    expect($message->fresh()->status)->toBe('read');
});

test('markAsReplied sets status to replied and sets status changed at', function () {
    $message = makeMessage();
    $message->markAsReplied();

    $fresh = $message->fresh();
    expect($fresh->status)->toBe('replied');
    expect($fresh->status_changed_at)->not->toBeNull();
});

test('markAsIgnored sets status to ignored and sets status changed at', function () {
    $message = makeMessage();
    $message->markAsIgnored();

    $fresh = $message->fresh();
    expect($fresh->status)->toBe('ignored');
    expect($fresh->status_changed_at)->not->toBeNull();
});

test('markAsActioned sets status to actioned and sets status changed at', function () {
    $message = makeMessage();
    $message->markAsActioned();

    $fresh = $message->fresh();
    expect($fresh->status)->toBe('actioned');
    expect($fresh->status_changed_at)->not->toBeNull();
});

test('new scope returns only new messages', function () {
    makeMessage(['status' => 'new', 'email' => 'new@example.com']);
    makeMessage(['status' => 'read', 'email' => 'read@example.com']);
    makeMessage(['status' => 'replied', 'email' => 'replied@example.com']);

    $results = ContactMessage::new()->pluck('email');

    expect($results)->toContain('new@example.com');
    expect($results)->not->toContain('read@example.com');
    expect($results)->not->toContain('replied@example.com');
});

test('unread scope returns new and read messages', function () {
    makeMessage(['status' => 'new', 'email' => 'new@example.com']);
    makeMessage(['status' => 'read', 'email' => 'read@example.com']);
    makeMessage(['status' => 'replied', 'email' => 'replied@example.com']);
    makeMessage(['status' => 'ignored', 'email' => 'ignored@example.com']);

    $results = ContactMessage::unread()->pluck('email');

    expect($results)->toContain('new@example.com');
    expect($results)->toContain('read@example.com');
    expect($results)->not->toContain('replied@example.com');
    expect($results)->not->toContain('ignored@example.com');
});

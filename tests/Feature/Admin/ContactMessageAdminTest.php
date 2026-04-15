<?php

use App\Livewire\Admin\ContactMessageList;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function contactAdminUser(): User
{
    return User::factory()->create(['email' => 'aclearmonth@gmail.com']);
}

function makeContactMessage(array $attributes = []): ContactMessage
{
    return ContactMessage::create(array_merge([
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'subject' => 'Test subject message',
        'message' => 'This is a test message body.',
        'type' => 'general',
        'status' => 'new',
    ], $attributes));
}

test('guests are redirected from admin contact messages', function () {
    $this->get('/admin/contact-messages')->assertRedirect('/login');
});

test('non-admin authenticated user is forbidden from admin contact messages', function () {
    $this->actingAs(User::factory()->create(['email' => 'user@example.com']));

    $this->get('/admin/contact-messages')->assertForbidden();
});

test('admin can view admin contact messages', function () {
    $this->actingAs(contactAdminUser());

    $this->get('/admin/contact-messages')->assertSuccessful();
});

test('admin contact messages list shows messages', function () {
    $this->actingAs(contactAdminUser());

    makeContactMessage(['name' => 'Visible Contact', 'subject' => 'Unique visible subject']);

    Livewire::test(ContactMessageList::class)->assertSee('Visible Contact');
});

test('admin contact messages search by name filters results', function () {
    $this->actingAs(contactAdminUser());

    makeContactMessage(['name' => 'Alice Wonder']);
    makeContactMessage(['name' => 'Bob Builder', 'email' => 'bob@example.com']);

    Livewire::test(ContactMessageList::class)
        ->set('search', 'Alice')
        ->assertSee('Alice Wonder')
        ->assertDontSee('Bob Builder');
});

test('admin contact messages search by email filters results', function () {
    $this->actingAs(contactAdminUser());

    makeContactMessage(['name' => 'Alpha User', 'email' => 'alpha@example.com']);
    makeContactMessage(['name' => 'Beta User', 'email' => 'beta@example.com']);

    Livewire::test(ContactMessageList::class)
        ->set('search', 'alpha@')
        ->assertSee('Alpha User')
        ->assertDontSee('Beta User');
});

test('admin contact messages search by subject filters results', function () {
    $this->actingAs(contactAdminUser());

    makeContactMessage(['subject' => 'Subject about Laravel']);
    makeContactMessage(['name' => 'Other', 'email' => 'other@example.com', 'subject' => 'Subject about PHP']);

    Livewire::test(ContactMessageList::class)
        ->set('search', 'about Laravel')
        ->assertSee('Subject about Laravel')
        ->assertDontSee('Subject about PHP');
});

test('admin contact messages filter by status works', function () {
    $this->actingAs(contactAdminUser());

    makeContactMessage(['name' => 'New Message', 'status' => 'new']);
    makeContactMessage(['name' => 'Read Message', 'email' => 'read@example.com', 'status' => 'read']);

    Livewire::test(ContactMessageList::class)
        ->set('status', 'new')
        ->assertSee('New Message')
        ->assertDontSee('Read Message');
});

test('admin contact messages filter by type works', function () {
    $this->actingAs(contactAdminUser());

    makeContactMessage(['name' => 'Work Request', 'type' => 'work_request', 'email' => 'work@example.com']);
    makeContactMessage(['name' => 'General Query', 'type' => 'general', 'email' => 'general@example.com']);

    Livewire::test(ContactMessageList::class)
        ->set('type', 'work_request')
        ->assertSee('Work Request')
        ->assertDontSee('General Query');
});

test('admin contact message status can be updated to replied', function () {
    $this->actingAs(contactAdminUser());

    $message = makeContactMessage();

    Livewire::test(ContactMessageList::class)
        ->call('updateStatus', $message->id, 'replied');

    expect($message->fresh()->status)->toBe('replied');
});

test('admin contact message status can be updated to ignored', function () {
    $this->actingAs(contactAdminUser());

    $message = makeContactMessage();

    Livewire::test(ContactMessageList::class)
        ->call('updateStatus', $message->id, 'ignored');

    expect($message->fresh()->status)->toBe('ignored');
});

test('admin contact message status can be updated to actioned', function () {
    $this->actingAs(contactAdminUser());

    $message = makeContactMessage();

    Livewire::test(ContactMessageList::class)
        ->call('updateStatus', $message->id, 'actioned');

    expect($message->fresh()->status)->toBe('actioned');
});

test('admin contact message update status shows flash message', function () {
    $this->actingAs(contactAdminUser());

    $message = makeContactMessage();

    Livewire::test(ContactMessageList::class)
        ->call('updateStatus', $message->id, 'replied')
        ->assertSee('Status updated');
});

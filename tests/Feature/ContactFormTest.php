<?php

use App\Livewire\Contact;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('contact form renders', function () {
    Livewire::test(Contact::class)->assertSet('type', 'general');
});

test('contact form submits successfully with valid general data', function () {
    Livewire::test(Contact::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('subject', 'Hello there')
        ->set('message', 'This is a test message that is long enough.')
        ->set('type', 'general')
        ->call('submit')
        ->assertHasNoErrors();

    expect(ContactMessage::where('email', 'john@example.com')->exists())->toBeTrue();
    expect(ContactMessage::where('type', 'general')->first()->name)->toBe('John Doe');
});

test('contact form submits with work_request type', function () {
    Livewire::test(Contact::class)
        ->set('name', 'Jane Smith')
        ->set('email', 'jane@example.com')
        ->set('subject', 'Project inquiry here')
        ->set('message', 'I need a website built for my business.')
        ->set('type', 'work_request')
        ->set('budget', '$5000')
        ->set('timeline', '3 months')
        ->set('projectType', 'E-commerce')
        ->call('submit')
        ->assertHasNoErrors();

    $message = ContactMessage::where('email', 'jane@example.com')->first();
    expect($message->metadata)->toMatchArray([
        'budget' => '$5000',
        'timeline' => '3 months',
        'projectType' => 'E-commerce',
    ]);
});

test('contact form submits with partnership type', function () {
    Livewire::test(Contact::class)
        ->set('name', 'Partner Co')
        ->set('email', 'partner@example.com')
        ->set('subject', 'Partnership opportunity')
        ->set('message', 'We would love to work together on a project.')
        ->set('type', 'partnership')
        ->call('submit')
        ->assertHasNoErrors();

    expect(ContactMessage::where('type', 'partnership')->exists())->toBeTrue();
});

test('contact form validates required name', function () {
    Livewire::test(Contact::class)
        ->set('name', '')
        ->set('email', 'test@example.com')
        ->set('subject', 'Valid subject')
        ->set('message', 'A valid message that is long enough.')
        ->set('type', 'general')
        ->call('submit')
        ->assertHasErrors(['name']);
});

test('contact form rejects invalid email', function () {
    Livewire::test(Contact::class)
        ->set('name', 'Valid Name')
        ->set('email', 'not-an-email')
        ->set('subject', 'Valid subject')
        ->set('message', 'A valid message that is long enough.')
        ->set('type', 'general')
        ->call('submit')
        ->assertHasErrors(['email']);
});

test('contact form rejects name too short', function () {
    Livewire::test(Contact::class)
        ->set('name', 'A')
        ->set('email', 'test@example.com')
        ->set('subject', 'Valid subject')
        ->set('message', 'A valid message that is long enough.')
        ->set('type', 'general')
        ->call('submit')
        ->assertHasErrors(['name']);
});

test('contact form rejects message too short', function () {
    Livewire::test(Contact::class)
        ->set('name', 'Valid Name')
        ->set('email', 'test@example.com')
        ->set('subject', 'Valid subject')
        ->set('message', 'Short msg')
        ->set('type', 'general')
        ->call('submit')
        ->assertHasErrors(['message']);
});

test('contact form rejects subject too short', function () {
    Livewire::test(Contact::class)
        ->set('name', 'Valid Name')
        ->set('email', 'test@example.com')
        ->set('subject', 'Hi')
        ->set('message', 'A valid message that is long enough.')
        ->set('type', 'general')
        ->call('submit')
        ->assertHasErrors(['subject']);
});

test('contact form rejects invalid type', function () {
    Livewire::test(Contact::class)
        ->set('name', 'Valid Name')
        ->set('email', 'test@example.com')
        ->set('subject', 'Valid subject')
        ->set('message', 'A valid message that is long enough.')
        ->set('type', 'spam')
        ->call('submit')
        ->assertHasErrors(['type']);
});

test('contact form shows success flash message after submit', function () {
    Livewire::test(Contact::class)
        ->set('name', 'Flash Tester')
        ->set('email', 'flash@example.com')
        ->set('subject', 'Flash test subject')
        ->set('message', 'This message should trigger the flash.')
        ->set('type', 'general')
        ->call('submit')
        ->assertSee('Thank you for your message');
});

test('contact form resets fields after submit', function () {
    Livewire::test(Contact::class)
        ->set('name', 'Reset Tester')
        ->set('email', 'reset@example.com')
        ->set('subject', 'Reset subject here')
        ->set('message', 'This message will be cleared on submit.')
        ->set('type', 'general')
        ->call('submit')
        ->assertSet('name', '')
        ->assertSet('email', '')
        ->assertSet('subject', '')
        ->assertSet('message', '')
        ->assertSet('type', 'general');
});

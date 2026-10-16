<?php

use App\Livewire\Auth\Register;
use App\Livewire\Contact;
use App\Mail\AdminNotificationBurst;
use App\Mail\NewContactMessage;
use App\Mail\NewUserSignedUp;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutDefer();

    Mail::fake();

    config()->set('services.admin_notify.email', 'admin@example.com');
});

function registerUser(string $email = 'new@example.com'): Testable
{
    return Livewire::test(Register::class)
        ->set('name', 'New User')
        ->set('email', $email)
        ->set('password', 'password')
        ->call('register');
}

function submitContactForm(string $email = 'sender@example.com'): Testable
{
    return Livewire::test(Contact::class)
        ->set('name', 'Sender Name')
        ->set('email', $email)
        ->set('subject', 'A question for you')
        ->set('message', "First line of the message.\nSecond line.")
        ->set('type', 'general')
        ->call('submit');
}

test('a sign-up emails the admin with the user details and total users', function () {
    User::factory()->count(2)->create();

    registerUser()->assertHasNoErrors();

    Mail::assertSent(NewUserSignedUp::class, function (NewUserSignedUp $mail): bool {
        return $mail->hasTo('admin@example.com')
            && $mail->user->email === 'new@example.com'
            && $mail->totalUsers === 3;
    });
});

test('the sign-up email shows the name, email, London time and referrer', function () {
    $user = User::factory()->create([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
        'created_at' => '2026-07-01 12:00:00',
    ]);

    $mail = new NewUserSignedUp($user, 7, 'https://news.example.com/post');

    $mail->assertSeeInHtml('Ada Lovelace');
    $mail->assertSeeInHtml('ada@example.com');
    $mail->assertSeeInHtml('01 Jul 2026, 13:00 BST');
    $mail->assertSeeInHtml('7');
    $mail->assertSeeInHtml('https://news.example.com/post');
});

test('a sign-up passes on the referrer captured when the form opened', function () {
    Livewire::withHeaders(['Referer' => 'https://news.example.com/post'])
        ->test(Register::class)
        ->set('name', 'New User')
        ->set('email', 'new@example.com')
        ->set('password', 'password')
        ->call('register');

    Mail::assertSent(NewUserSignedUp::class, fn (NewUserSignedUp $mail): bool => $mail->referrer === 'https://news.example.com/post');
});

test('a contact message emails the admin, replying to the sender', function () {
    submitContactForm()->assertHasNoErrors();

    $contactMessage = ContactMessage::sole();

    Mail::assertSent(NewContactMessage::class, function (NewContactMessage $mail) use ($contactMessage): bool {
        return $mail->hasTo('admin@example.com')
            && $mail->hasReplyTo('sender@example.com', 'Sender Name')
            && $mail->contactMessage->is($contactMessage);
    });
});

test('the contact email shows the sender, subject, message text and admin link', function () {
    $contactMessage = ContactMessage::create([
        'type' => 'general',
        'name' => 'Sender Name',
        'email' => 'sender@example.com',
        'subject' => 'A question for you',
        'message' => "First line of the message.\nSecond line.",
    ]);

    $mail = new NewContactMessage($contactMessage);

    $mail->assertSeeInHtml('Sender Name');
    $mail->assertSeeInHtml('sender@example.com');
    $mail->assertSeeInHtml('A question for you');
    $mail->assertSeeInOrderInHtml(['First line of the message.', '<br />', 'Second line.'], escape: false);
    $mail->assertSeeInHtml(route('admin.contact-messages.index', ['search' => 'sender@example.com']));
});

test('no emails are sent when no admin address is set', function () {
    config()->set('services.admin_notify.email', '');

    registerUser()->assertHasNoErrors();
    submitContactForm()->assertHasNoErrors();

    Mail::assertNothingSent();
});

test('a mail failure does not break sign-up', function () {
    Exceptions::fake();
    Mail::shouldReceive('to')->andThrow(new RuntimeException('Resend is down'));

    registerUser()
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
    Exceptions::assertReported(fn (RuntimeException $exception): bool => $exception->getMessage() === 'Resend is down');
});

test('a mail failure does not break the contact form', function () {
    Exceptions::fake();
    Mail::shouldReceive('to')->andThrow(new RuntimeException('Resend is down'));

    submitContactForm()->assertHasNoErrors();

    expect(ContactMessage::count())->toBe(1);
    Exceptions::assertReported(RuntimeException::class);
});

test('more than five sign-ups in ten minutes become one summary', function () {
    foreach (range(1, 8) as $number) {
        registerUser("user{$number}@example.com");
        auth()->logout();
    }

    Mail::assertSent(NewUserSignedUp::class, 5);
    Mail::assertSent(AdminNotificationBurst::class, 1);
    Mail::assertSent(AdminNotificationBurst::class, fn (AdminNotificationBurst $mail): bool => $mail->description === 'sign ups');
});

test('more than five contact messages in ten minutes become one summary', function () {
    foreach (range(1, 7) as $number) {
        submitContactForm("sender{$number}@example.com");
    }

    Mail::assertSent(NewContactMessage::class, 5);
    Mail::assertSent(AdminNotificationBurst::class, 1);
});

test('individual emails resume once the burst window has passed', function () {
    foreach (range(1, 6) as $number) {
        submitContactForm("sender{$number}@example.com");
    }

    $this->travel(11)->minutes();

    submitContactForm('later@example.com');

    Mail::assertSent(NewContactMessage::class, 6);
    Mail::assertSent(NewContactMessage::class, fn (NewContactMessage $mail): bool => $mail->contactMessage->email === 'later@example.com');
});

test('sign-ups and contact messages are guarded separately', function () {
    foreach (range(1, 6) as $number) {
        submitContactForm("sender{$number}@example.com");
    }

    registerUser()->assertHasNoErrors();

    Mail::assertSent(NewUserSignedUp::class, 1);
});

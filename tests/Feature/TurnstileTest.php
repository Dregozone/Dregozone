<?php

use App\Livewire\Contact;
use App\Livewire\NewsletterSignup;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Rules\Turnstile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    config([
        'services.turnstile.site_key' => 'test-site-key',
        'services.turnstile.secret_key' => 'test-secret-key',
    ]);
});

function fakeTurnstile(bool $success): void
{
    Http::fake([Turnstile::VERIFY_URL => Http::response(['success' => $success])]);
}

function fillContactForm(): Testable
{
    return Livewire::test(Contact::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('subject', 'Hello there')
        ->set('message', 'This is a test message that is long enough.');
}

test('the contact form and newsletter signup show the turnstile widget when it is configured', function () {
    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('challenges.cloudflare.com/turnstile/v0/api.js', false)
        ->assertSee('test-site-key');

    Livewire::test(NewsletterSignup::class)->assertSeeHtml('x-ref="widget"');
});

test('the turnstile widget is left out when it is not configured', function () {
    config(['services.turnstile.site_key' => null]);

    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertDontSee('challenges.cloudflare.com', false);
});

test('the contact form sends when the turnstile check passes', function () {
    fakeTurnstile(true);

    fillContactForm()
        ->set('turnstileToken', 'valid-token')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('turnstileToken', '')
        ->assertDispatched('turnstile-reset');

    expect(ContactMessage::where('email', 'john@example.com')->exists())->toBeTrue();

    Http::assertSent(fn (Request $request) => $request->url() === Turnstile::VERIFY_URL
        && $request['secret'] === 'test-secret-key'
        && $request['response'] === 'valid-token');
});

test('the contact form is rejected when the turnstile check fails', function () {
    fakeTurnstile(false);

    fillContactForm()
        ->set('turnstileToken', 'bad-token')
        ->call('submit')
        ->assertHasErrors(['turnstileToken'])
        ->assertDispatched('turnstile-reset');

    expect(ContactMessage::count())->toBe(0);
});

test('the contact form is rejected without a turnstile token', function () {
    Http::fake();

    fillContactForm()
        ->call('submit')
        ->assertHasErrors(['turnstileToken']);

    expect(ContactMessage::count())->toBe(0);
    Http::assertNothingSent();
});

test('the turnstile check is not made while other contact fields are invalid', function () {
    Http::fake();

    fillContactForm()
        ->set('email', 'not-an-email')
        ->set('turnstileToken', 'valid-token')
        ->call('submit')
        ->assertHasErrors(['email'])
        ->assertSet('turnstileToken', 'valid-token');

    Http::assertNothingSent();
});

test('the newsletter signup subscribes when the turnstile check passes', function () {
    fakeTurnstile(true);

    Livewire::test(NewsletterSignup::class)
        ->set('email', 'subscriber@example.com')
        ->set('turnstileToken', 'valid-token')
        ->call('subscribe')
        ->assertHasNoErrors()
        ->assertSet('subscribed', true);

    expect(NewsletterSubscriber::where('email', 'subscriber@example.com')->exists())->toBeTrue();
});

test('the newsletter signup is rejected when the turnstile check fails', function () {
    fakeTurnstile(false);

    Livewire::test(NewsletterSignup::class)
        ->set('email', 'subscriber@example.com')
        ->set('turnstileToken', 'bad-token')
        ->call('subscribe')
        ->assertHasErrors(['turnstileToken'])
        ->assertSet('subscribed', false);

    expect(NewsletterSubscriber::count())->toBe(0);
});

test('the turnstile rule fails when cloudflare cannot be reached', function () {
    Http::fake(fn () => throw new ConnectionException('Connection timed out'));

    $validator = Validator::make(['token' => 'valid-token'], ['token' => [new Turnstile]]);

    expect($validator->fails())->toBeTrue();
});

test('the turnstile rule passes without a check when no secret key is set', function () {
    config(['services.turnstile.secret_key' => null]);
    Http::fake();

    $validator = Validator::make(['token' => ''], ['token' => [new Turnstile]]);

    expect($validator->passes())->toBeTrue();
    Http::assertNothingSent();
});

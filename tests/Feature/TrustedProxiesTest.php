<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::get('/_test/request-info', fn (Request $request) => [
        'ip' => $request->ip(),
        'secure' => $request->secure(),
        'host' => $request->getHost(),
    ]);
});

test('the visitor ip is read from x-forwarded-for when the request comes from cloudflare', function (string $cloudflareIp) {
    $this->withServerVariables(['REMOTE_ADDR' => $cloudflareIp])
        ->withHeaders(['X-Forwarded-For' => '203.0.113.9', 'X-Forwarded-Proto' => 'https'])
        ->getJson('/_test/request-info')
        ->assertJson(['ip' => '203.0.113.9', 'secure' => true]);
})->with([
    'ipv4 edge' => '173.245.48.10',
    'ipv6 edge' => '2606:4700::1',
]);

test('a visitor-supplied x-forwarded-for passed along by cloudflare cannot override the real ip', function () {
    // Cloudflare appends the connecting IP to whatever the visitor sent.
    $this->withServerVariables(['REMOTE_ADDR' => '162.158.1.1'])
        ->withHeaders(['X-Forwarded-For' => '10.0.0.1, 203.0.113.9'])
        ->getJson('/_test/request-info')
        ->assertJson(['ip' => '203.0.113.9']);
});

test('a forged x-forwarded-for sent straight to the origin is ignored', function () {
    $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.7'])
        ->withHeaders(['X-Forwarded-For' => '203.0.113.9', 'X-Forwarded-Proto' => 'https'])
        ->getJson('/_test/request-info')
        ->assertJson(['ip' => '198.51.100.7', 'secure' => false]);
});

test('x-forwarded-host from cloudflare is not trusted', function () {
    $this->withServerVariables(['REMOTE_ADDR' => '173.245.48.10'])
        ->withHeaders(['X-Forwarded-For' => '203.0.113.9', 'X-Forwarded-Host' => 'evil.example'])
        ->getJson('/_test/request-info')
        ->assertJsonMissing(['host' => 'evil.example']);
});

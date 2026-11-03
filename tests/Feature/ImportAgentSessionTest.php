<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->transcript = storage_path('framework/testing/transcript-'.uniqid().'.jsonl');
    $this->output = resource_path('data/agent-sessions/pest-fixture.json');
    File::ensureDirectoryExists(dirname($this->transcript));

    $root = str_replace('\\', '/', base_path());
    $line = fn (string $type, array $content, string $at): string => json_encode([
        'type' => $type, 'timestamp' => $at, 'isSidechain' => false, 'message' => ['content' => $content],
    ]);

    File::put($this->transcript, implode("\n", [
        $line('user', [['type' => 'text', 'text' => 'the admin list is stale until I refresh']], '2026-11-03T09:00:00Z'),
        $line('assistant', [['type' => 'text', 'text' => 'Looking at the service worker first. It caches too much.']], '2026-11-03T09:00:05Z'),
        $line('assistant', [['type' => 'tool_use', 'id' => 't1', 'name' => 'Read', 'input' => ['file_path' => "{$root}/public/sw.js"]]], '2026-11-03T09:00:06Z'),
        $line('user', [['type' => 'tool_result', 'tool_use_id' => 't1', 'content' => "line one\nline two\nline three"]], '2026-11-03T09:00:07Z'),
        $line('assistant', [['type' => 'tool_use', 'id' => 't2', 'name' => 'Edit', 'input' => ['file_path' => "{$root}/public/sw.js", 'old_string' => "const CACHE_NAME = 'v1';", 'new_string' => "const CACHE_NAME = 'v2';"]]], '2026-11-03T09:01:00Z'),
        $line('user', [['type' => 'tool_result', 'tool_use_id' => 't2', 'content' => 'ok']], '2026-11-03T09:01:01Z'),
        $line('assistant', [['type' => 'tool_use', 'id' => 't3', 'name' => 'Bash', 'input' => ['command' => 'echo C:\\Users\\someone\\secrets anders@example.com abcdefghijklmnopqrstuvwxyz0123456789']]], '2026-11-03T09:02:00Z'),
        $line('user', [['type' => 'tool_result', 'tool_use_id' => 't3', 'content' => 'boom', 'is_error' => true]], '2026-11-03T09:02:01Z'),
        $line('assistant', [['type' => 'tool_use', 'id' => 't4', 'name' => 'Bash', 'input' => ['command' => 'php artisan test --compact']]], '2026-11-03T09:03:00Z'),
        $line('user', [['type' => 'tool_result', 'tool_use_id' => 't4', 'content' => "  Tests:    3 passed (9 assertions)\n"]], '2026-11-03T09:03:30Z'),
        $line('assistant', [['type' => 'text', 'text' => 'Fixed and verified. Unrelated chatter follows.']], '2026-11-03T09:04:00Z'),
        $line('assistant', [['type' => 'text', 'text' => 'Something else entirely.']], '2026-11-03T09:05:00Z'),
    ]));
});

afterEach(function () {
    File::delete([$this->transcript, $this->output]);
});

test('it imports a curated window of a transcript as a replay', function () {
    $this->artisan('agent-session:import', [
        'transcript' => $this->transcript,
        'name' => 'pest-fixture',
        '--from' => 'Looking at the service worker',
        '--to' => 'Fixed and verified',
        '--title' => 'Stale admin list',
        '--shipped' => 'PR #1',
    ])->assertSuccessful();

    $replay = json_decode(File::get($this->output), true);

    expect($replay)
        ->title->toBe('Stale admin list')
        ->shipped->toBe('PR #1')
        ->date->toBe('2026-11-03')
        ->prompt->toBe('> the admin list is stale until I refresh')
        ->and($replay['duration_seconds'])->toBeGreaterThan(200)
        ->and(array_column($replay['events'], 'type'))->toBe(['say', 'tool', 'diff', 'tool', 'tool', 'say'])
        ->and($replay['events'][0]['text'])->toBe('Looking at the service worker first.')
        ->and($replay['events'][1])->toMatchArray(['call' => 'Read(public/sw.js)', 'result' => 'Read 2 lines'])
        ->and($replay['events'][2]['lines'])->toBe([['-', "const CACHE_NAME = 'v1';"], ['+', "const CACHE_NAME = 'v2';"]])
        ->and($replay['events'][3]['status'])->toBe('error')
        ->and($replay['events'][4])->toMatchArray(['status' => 'ok', 'result' => 'Tests:    3 passed (9 assertions)'])
        ->and(collect($replay['events'])->pluck('delay')->every(fn (int $delay): bool => $delay >= 450 && $delay <= 2200))->toBeTrue();
});

test('it redacts machine paths, emails and token-like strings', function () {
    $this->artisan('agent-session:import', ['transcript' => $this->transcript, 'name' => 'pest-fixture'])->assertSuccessful();

    expect(File::get($this->output))
        ->not->toContain('someone')
        ->not->toContain('anders@example.com')
        ->not->toContain('abcdefghijklmnopqrstuvwxyz0123456789')
        ->toContain('[email]')
        ->toContain('[redacted]');
});

test('it stitches several windows together in transcript order', function () {
    $this->artisan('agent-session:import', [
        'transcript' => $this->transcript,
        'name' => 'pest-fixture',
        '--window' => ['php artisan test|php artisan test', 'Looking at the service worker|Looking at the service worker'],
    ])->assertSuccessful();

    $events = json_decode(File::get($this->output), true)['events'];

    expect(array_column($events, 'type'))->toBe(['say', 'tool'])
        ->and($events[1]['call'])->toBe('Bash(php artisan test --compact)');
});

test('it fails clearly for a missing transcript', function () {
    $this->artisan('agent-session:import', ['transcript' => 'missing.jsonl', 'name' => 'pest-fixture'])
        ->expectsOutputToContain('Transcript not found')
        ->assertFailed();
});

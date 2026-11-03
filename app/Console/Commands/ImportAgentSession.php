<?php

namespace App\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Turns a slice of a Claude Code transcript (JSONL) into a small, redacted replay
 * file for the home page's agent session terminal.
 */
class ImportAgentSession extends Command
{
    protected $signature = 'agent-session:import
        {transcript : Path to a Claude Code transcript (.jsonl)}
        {name : File name for the replay, e.g. stale-admin-blog-list}
        {--from= : Start at the first event containing this text}
        {--to= : Stop after the first later event containing this text}
        {--window=* : One or more "from|to" windows to stitch together (instead of --from/--to)}
        {--prompt= : The prompt to display (defaults to the nearest real prompt)}
        {--title= : Short title shown in the terminal header}
        {--shipped= : What the session shipped, e.g. "PR #68"}
        {--max-events=18 : Maximum number of events to keep}';

    protected $description = 'Import a curated, redacted Claude Code session for the home page replay';

    /**
     * Tools that add noise rather than story to a replay.
     *
     * @var list<string>
     */
    protected array $ignoredTools = ['ToolSearch', 'TaskOutput', 'TaskStop', 'Monitor', 'ScheduleWakeup', 'AskUserQuestion'];

    public function handle(): int
    {
        $path = $this->argument('transcript');

        if (! File::exists($path)) {
            $this->error("Transcript not found: {$path}");

            return self::FAILURE;
        }

        $events = $this->parse($path);
        $windows = $this->option('window') ?: [$this->option('from').'|'.$this->option('to')];
        $selected = collect($windows)
            ->flatMap(function (string $window) use ($events): array {
                [$from, $to] = array_pad(explode('|', $window, 2), 2, '');

                return $this->select($events, $from !== '' ? $from : null, $to !== '' ? $to : null);
            })
            ->unique('index')
            ->sortBy('index')
            ->values()
            ->all();

        if ($selected === []) {
            $this->error('No events matched the --from/--to window.');

            return self::FAILURE;
        }

        $prompt = $this->option('prompt') ?: $this->nearestPrompt($events, $selected[0]['index']);
        $selected = array_values(array_filter($selected, fn (array $event): bool => $event['type'] !== 'prompt'));
        $selected = array_slice($selected, 0, (int) $this->option('max-events'));

        $started = $selected[0]['at'];
        $ended = end($selected)['at'];

        $replay = [
            'title' => $this->option('title') ?: Str::headline($this->argument('name')),
            'project' => '~/'.basename(dirname(base_path())).'/'.basename(base_path()),
            'date' => $started->toDateString(),
            'duration_seconds' => max(1, $started->diffInSeconds($ended)),
            'shipped' => $this->option('shipped'),
            'prompt' => $this->redact($prompt),
            'events' => $this->withTiming($selected),
        ];

        $directory = resource_path('data/agent-sessions');
        File::ensureDirectoryExists($directory);
        File::put("{$directory}/{$this->argument('name')}.json", json_encode($replay, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).PHP_EOL);

        $this->info(sprintf('Saved %d events (%s of real time) to resources/data/agent-sessions/%s.json',
            count($replay['events']), CarbonImmutable::now()->subSeconds($replay['duration_seconds'])->diffForHumans(syntax: true, parts: 2), $this->argument('name')));

        return self::SUCCESS;
    }

    /**
     * Flattens the transcript into ordered prompt/say/tool events, attaching each tool's result.
     *
     * @return list<array{index: int, type: string, at: CarbonImmutable, text?: string, call?: string, result?: string, status?: string, lines?: list<array{0: string, 1: string}>}>
     */
    protected function parse(string $path): array
    {
        $events = [];
        $toolEvents = [];

        foreach (File::lines($path) as $line) {
            $record = json_decode($line, true);

            if (! is_array($record) || ! in_array($record['type'] ?? null, ['user', 'assistant'], true) || ($record['isSidechain'] ?? false)) {
                continue;
            }

            $at = CarbonImmutable::parse($record['timestamp'] ?? 'now');
            $content = $record['message']['content'] ?? [];

            if (is_string($content)) {
                $content = [['type' => 'text', 'text' => $content]];
            }

            foreach ($content as $block) {
                $kind = $block['type'] ?? null;

                if ($record['type'] === 'user' && $kind === 'text' && $this->isRealPrompt($block['text'])) {
                    $events[] = ['index' => count($events), 'type' => 'prompt', 'at' => $at, 'text' => trim($block['text'])];
                } elseif ($record['type'] === 'assistant' && $kind === 'text' && trim($block['text']) !== '') {
                    $events[] = ['index' => count($events), 'type' => 'say', 'at' => $at, 'text' => $this->firstSentence($this->redact($block['text']))];
                } elseif ($kind === 'tool_use' && ! in_array($block['name'], $this->ignoredTools, true)) {
                    $event = ['index' => count($events), 'type' => 'tool', 'at' => $at, 'call' => $this->describeCall($block['name'], $block['input'] ?? []), 'result' => '', 'status' => 'info'];

                    if (in_array($block['name'], ['Edit', 'Write'], true)) {
                        $event['lines'] = $this->diffLines(array_map(fn (mixed $value): mixed => is_string($value) ? $this->redact($value) : $value, $block['input'] ?? []));
                    }

                    $events[] = $event;
                    $toolEvents[$block['id']] = array_key_last($events);
                } elseif ($kind === 'tool_result' && isset($toolEvents[$block['tool_use_id'] ?? ''])) {
                    $position = $toolEvents[$block['tool_use_id']];
                    $text = $this->redact($this->resultText($block['content'] ?? ''));
                    $events[$position]['result'] = $this->summariseResult($events[$position]['call'], $text);
                    $summary = $events[$position]['result'];
                    $events[$position]['status'] = match (true) {
                        ($block['is_error'] ?? false),
                        (bool) preg_match('/\b(FAILED|Traceback|Fatal error)\b|exit code [1-9]/i', $text),
                        (bool) preg_match('/(Error|Exception|Uplink|TIMEOUT|blocked)/', $summary) => 'error',
                        (bool) preg_match('/\b(passed|DONE|READY|saved|success)\b/i', $summary) => 'ok',
                        default => 'info',
                    };
                }
            }
        }

        return $events;
    }

    /**
     * @param  list<array<string, mixed>>  $events
     * @return list<array<string, mixed>>
     */
    protected function select(array $events, ?string $from, ?string $to): array
    {
        $start = $from === null ? 0 : $this->findEvent($events, $from, 0);

        if ($start === null) {
            return [];
        }

        // "to" may match the start event itself (a single-event window); if it never matches, keep just the start.
        $end = $to === null ? count($events) - 1 : ($this->findEvent($events, $to, $start) ?? $start);

        return array_slice($events, $start, $end - $start + 1);
    }

    /**
     * @param  list<array<string, mixed>>  $events
     */
    protected function findEvent(array $events, string $needle, int $offset): ?int
    {
        for ($i = $offset; $i < count($events); $i++) {
            $haystack = ($events[$i]['text'] ?? '').' '.($events[$i]['call'] ?? '').' '.($events[$i]['result'] ?? '');

            if (Str::contains($haystack, $needle, ignoreCase: true)) {
                return $i;
            }
        }

        return null;
    }

    /**
     * @param  list<array<string, mixed>>  $events
     */
    protected function nearestPrompt(array $events, int $index): string
    {
        for ($i = $index; $i >= 0; $i--) {
            if ($events[$i]['type'] === 'prompt') {
                return '> '.Str::limit(Str::squish($events[$i]['text']), 110);
            }
        }

        return '>';
    }

    /**
     * Compresses real gaps between events into replay delays (fast enough to watch, slow enough to read).
     *
     * @param  list<array<string, mixed>>  $events
     * @return list<array<string, mixed>>
     */
    protected function withTiming(array $events): array
    {
        $previous = $events[0]['at'];

        return array_map(function (array $event) use (&$previous): array {
            $gap = $previous->diffInMilliseconds($event['at']);
            $previous = $event['at'];

            $replayed = array_filter([
                'type' => isset($event['lines']) && $event['lines'] !== [] ? 'diff' : $event['type'],
                'text' => isset($event['text']) ? $this->redact($event['text']) : null,
                'call' => isset($event['call']) ? $this->redact($event['call']) : null,
                'result' => isset($event['result']) && $event['result'] !== '' ? $this->redact($event['result']) : null,
                'status' => $event['status'] ?? null,
                'lines' => isset($event['lines']) ? array_map(fn (array $line): array => [$line[0], $this->redact($line[1])], $event['lines']) : null,
            ], fn ($value): bool => $value !== null);

            // Real gaps range from milliseconds to minutes; a log scale keeps their shape without the waiting.
            $replayed['delay'] = (int) min(2200, max(450, 450 + log(1 + $gap / 1000) * 420));

            return $replayed;
        }, $events);
    }

    protected function isRealPrompt(string $text): bool
    {
        $text = trim($text);

        return $text !== '' && ! Str::startsWith($text, ['<', '[', 'Caveat:']) && ! Str::contains($text, '<system-reminder>');
    }

    protected function firstSentence(string $text): string
    {
        $text = Str::squish(preg_replace('/[*`#>]+/', '', $text));
        $sentence = preg_split('/(?<=[.!?])\s+/', $text)[0] ?? $text;

        return Str::limit($sentence, 140);
    }

    /**
     * @param  array<string, mixed>  $input
     */
    protected function describeCall(string $tool, array $input): string
    {
        // Redact before anything is truncated, so a cut-off secret can never slip under the patterns.
        $input = array_map(fn (mixed $value): mixed => is_string($value) && $tool !== 'Read' ? $this->redact($value) : $value, $input);

        $file = isset($input['file_path']) ? $this->relativePath($input['file_path']) : null;

        return match ($tool) {
            'Read', 'Write', 'Edit' => "{$tool}({$file})",
            'Grep' => sprintf('Grep("%s"%s)', Str::limit($input['pattern'] ?? '', 40), isset($input['path']) ? ', '.$this->relativePath($input['path']) : ''),
            'Glob' => sprintf('Glob("%s")', $input['pattern'] ?? ''),
            'Bash', 'PowerShell' => $this->describeShell($tool, $input),
            default => "{$tool}()",
        };
    }

    /**
     * Short commands read best verbatim; long pipelines read best as their human description.
     *
     * @param  array<string, mixed>  $input
     */
    protected function describeShell(string $tool, array $input): string
    {
        $command = $this->cleanCommand($input['command'] ?? '');

        $isScript = str_contains(trim($input['command'] ?? ''), "\n");

        if (empty($input['description']) || (! $isScript && mb_strlen($command) <= 56)) {
            return sprintf('%s(%s)', $tool, Str::limit($command, 70));
        }

        return sprintf('%s · %s', $tool, Str::limit($input['description'], 70));
    }

    protected function cleanCommand(string $command): string
    {
        $command = preg_replace('/^cd\s+\S+\s*&&\s*/', '', trim($command));
        $command = Str::before($command, "\n");

        return Str::squish(preg_replace('/\s*(2>&1|\|\s*(tail|head|grep)[^|]*)+$/', '', $command));
    }

    /**
     * @param  array<string, mixed>  $input
     * @return list<array{0: string, 1: string}>
     */
    protected function diffLines(array $input): array
    {
        $meaningful = fn (string $text): array => array_values(array_filter(
            array_map('rtrim', explode("\n", $text)),
            fn (string $line): bool => trim($line) !== '' && ! Str::startsWith(trim($line), ['//', '*', '/*', '{{--'])
        ));

        $removed = array_slice($meaningful($input['old_string'] ?? ''), 0, 3);
        $added = array_slice($meaningful($input['new_string'] ?? $input['content'] ?? ''), 0, 5);

        return [
            ...array_map(fn (string $line): array => ['-', Str::limit(trim($line), 90)], array_diff($removed, $added)),
            ...array_map(fn (string $line): array => ['+', Str::limit(trim($line), 90)], array_diff($added, $removed)),
        ];
    }

    protected function resultText(mixed $content): string
    {
        if (is_array($content)) {
            $content = implode("\n", array_map(fn ($part): string => is_array($part) ? ($part['text'] ?? '') : (string) $part, $content));
        }

        return preg_replace('/\e\[[\d;]*m/', '', (string) $content);
    }

    protected function summariseResult(string $call, string $text): string
    {
        if (preg_match('/Tests:\s+.*$/m', $text, $match)) {
            return Str::limit(trim($match[0]), 90);
        }

        if (Str::startsWith($call, 'Read(')) {
            return 'Read '.substr_count($text, "\n").' lines';
        }

        if (Str::startsWith($call, ['Edit(', 'Write('])) {
            return Str::startsWith($call, 'Write(') ? 'Created' : 'Updated';
        }

        $squished = Str::squish($text);

        if (Str::startsWith($squished, 'Command running in background')) {
            return 'started in background';
        }

        if (Str::contains($squished, 'did not complete within')) {
            return 'still waiting, moved to background';
        }

        if (Str::startsWith($squished, 'Permission for this action was denied')) {
            return 'blocked by the permission policy';
        }

        $lines = array_values(array_filter(array_map('trim', explode("\n", $text)), fn (string $line): bool => $line !== '' && ! Str::startsWith($line, ['<'])));

        // The line that explains a failure is worth more than the exit code that follows it.
        foreach (array_reverse($lines) as $line) {
            if (preg_match('/(Error|Exception|Uplink|No module named)\b/', $line) && ! Str::contains($line, 'exited with code')) {
                return Str::limit(trim(Str::afterLast($line, ']')), 90);
            }
        }

        foreach (array_reverse($lines) as $line) {
            if (preg_match('/\b(READY|saved|DONE|passed|failed|error|exited|true|false|built in)\b/i', $line)) {
                return Str::limit($line, 90);
            }
        }

        return match (true) {
            $lines === [] => 'done',
            count($lines) === 1 => Str::limit($lines[0], 90),
            default => count($lines).' lines of output',
        };
    }

    protected function relativePath(string $path): string
    {
        $normalised = str_replace('\\', '/', $path);
        $root = str_replace('\\', '/', base_path()).'/';

        return Str::startsWith($normalised, $root) ? Str::after($normalised, $root) : $this->redact($normalised);
    }

    /**
     * Removes anything that identifies the machine or could be a secret.
     */
    protected function redact(string $text): string
    {
        $root = str_replace('\\', '/', base_path());

        $text = str_replace([$root.'/', str_replace('/', '\\', $root).'\\'], '', $text);

        return preg_replace([
            '#(?:[A-Za-z]:|/[a-z])?[/\\\\]Users[/\\\\][^/\\\\\s"\']+[/\\\\]AppData[/\\\\]Local[/\\\\]Temp(?:[/\\\\][^/\\\\\s"\']+)*?[/\\\\]scratchpad#i',
            '#(?:[A-Za-z]:|/[a-z])?[/\\\\]Users[/\\\\][^/\\\\\s"\']+#i',
            '/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i',
            '/\b[A-Za-z0-9_\-]{32,}\b/',
        ], ['~/scratch', '~', '[email]', '[redacted]'], $text);
    }
}

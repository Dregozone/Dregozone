@props(['sessions'])

@php
    $first = $sessions[0] ?? null;
    $statusClass = fn (?string $status): string => match ($status) {
        'ok' => 'text-glow-teal',
        'error' => 'text-rose-300',
        default => 'text-dim',
    };
    $duration = fn (int $seconds): string => $seconds >= 60 ? intdiv($seconds, 60).'m '.($seconds % 60).'s' : $seconds.'s';
@endphp

{{--
    Replays real, curated Claude Code sessions (see `php artisan agent-session:import`).
    The first session is server-rendered in its final state, so it reads without JavaScript
    and for visitors who prefer reduced motion; motion.js replays and rotates the sessions.
--}}
@if ($first)
    <figure {{ $attributes->merge(['class' => 'aurora-glass overflow-hidden rounded-2xl']) }} data-agent-replay wire:ignore>
        <div class="flex items-center justify-between gap-3 border-b border-edge px-4 py-3 font-mono text-[11px]">
            <div class="flex min-w-0 items-center gap-3">
                <div class="flex shrink-0 gap-1.5" aria-hidden="true">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#ff5f57]/70"></span><span class="h-2.5 w-2.5 rounded-full bg-[#febc2e]/70"></span><span class="h-2.5 w-2.5 rounded-full bg-[#28c840]/70"></span>
                </div>
                <span class="truncate text-dim">replay · claude code · <span data-replay-project>{{ $first['project'] }}</span></span>
            </div>
            <div class="flex shrink-0 items-center gap-3">
                <span class="text-dim tabular-nums" data-replay-clock aria-hidden="true">{{ $duration((int) $first['duration_seconds']) }}</span>
                <span class="flex items-center gap-1.5 text-glow-teal" data-replay-state><span class="h-1.5 w-1.5 rounded-full bg-glow-teal"></span>done</span>
            </div>
        </div>

        @if (count($sessions) > 1)
            <div class="flex gap-1 overflow-x-auto border-b border-edge px-3 py-2 font-mono text-[11px]" role="tablist" aria-label="Agent sessions">
                @foreach ($sessions as $session)
                    <button type="button" role="tab" data-replay-tab="{{ $loop->index }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        class="shrink-0 rounded-md px-2.5 py-1 text-dim transition-colors hover:text-ink aria-selected:bg-white/5 aria-selected:text-ink">
                        {{ $session['title'] }}
                    </button>
                @endforeach
            </div>
        @endif

        {{-- A real terminal keeps the newest line in view; the mask fades older lines into the chrome. --}}
        <div class="relative h-[25rem] overflow-hidden [mask-image:linear-gradient(to_bottom,transparent,#000_2.5rem)]" data-replay-viewport>
            <div class="space-y-1.5 p-5 pt-8 font-mono text-[12.5px] leading-6" data-replay-log aria-live="off">
                <p class="text-ink">{{ $first['prompt'] }}</p>
                @foreach ($first['events'] as $event)
                    @if ($event['type'] === 'say')
                        <p class="text-mute"><span class="text-glow-ember">●</span> {{ $event['text'] }}</p>
                    @else
                        <div>
                            <p class="text-ink"><span class="text-glow-ember">●</span> {{ $event['call'] }}</p>
                            @if ($event['type'] === 'diff' && ! empty($event['lines']))
                                <div class="my-1 ml-4 overflow-hidden rounded-lg border border-edge bg-night">
                                    @foreach ($event['lines'] as [$sign, $code])
                                        <p @class(['truncate px-3', 'bg-glow-teal/5 text-glow-teal' => $sign === '+', 'text-rose-300' => $sign === '-'])>{{ $sign }} {{ $code }}</p>
                                    @endforeach
                                </div>
                            @elseif (! empty($event['result']))
                                <p class="truncate pl-4 {{ $statusClass($event['status'] ?? null) }}">└ {{ $event['result'] }}</p>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <figcaption class="flex flex-wrap items-center justify-between gap-2 border-t border-edge px-4 py-3 font-mono text-[11px] text-dim">
            <span>session <span data-replay-date>{{ \Illuminate\Support\Carbon::parse($first['date'])->format('j M Y') }}</span> · real transcript, lightly trimmed</span>
            <span class="text-glow-teal" data-replay-shipped>@if ($first['shipped']) → shipped {{ $first['shipped'] }} @endif</span>
        </figcaption>

        <script type="application/json" data-replay-sessions>@json($sessions)</script>
    </figure>
@endif

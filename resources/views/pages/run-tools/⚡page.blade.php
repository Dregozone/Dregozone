<?php

use Dregozone\RunTools\PaceCalculator;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.main')] class extends Component
{
    public int $distanceKm = 0;
    public int $distanceM = 0;
    public int $timeHours = 0;
    public int $timeMinutes = 0;
    public int $timeSecs = 0;
    public string $paceUnit = 'km';

    public string $requiredPace = '';

    public function calculate(): void
    {
        $totalMeters  = ($this->distanceKm * 1000) + $this->distanceM;
        $totalSeconds = ($this->timeHours * 3600) + ($this->timeMinutes * 60) + $this->timeSecs;

        $this->requiredPace = PaceCalculator::calculatePace(
            distance: $totalMeters,
            time: $totalSeconds,
            unit: $this->paceUnit
        );
    }

    public function valueChanged(): void
    {
        $this->reset(['requiredPace']);
    }
};
?>

<div>
    <x-aurora.page-header path="~/tools/pace" eyebrow="./pace --calculate"
        description="Enter your target distance and finish time to instantly calculate the running pace you'll need to maintain — in kilometres or miles.">
        <x-slot:title>Pace Calculator</x-slot:title>
    </x-aurora.page-header>

    <section class="px-6 py-14 lg:py-20">
        <div class="mx-auto grid max-w-5xl gap-6 lg:grid-cols-[1.15fr_1fr] lg:items-start">
            <div class="aurora-glass rounded-2xl p-6 sm:p-8" data-reveal>
                <fieldset class="space-y-8">
                    <legend class="sr-only">Pace calculator inputs</legend>

                    <div>
                        <p class="mb-3 font-mono text-xs text-glow-teal" id="distance-group-label">// distance</p>
                        <div class="grid grid-cols-2 gap-4" role="group" aria-labelledby="distance-group-label">
                            <flux:field>
                                <flux:label for="distanceKm">Kilometres</flux:label>
                                <flux:input id="distanceKm" wire:model="distanceKm" wire:change="valueChanged" type="number" min="0" placeholder="0" />
                            </flux:field>
                            <flux:field>
                                <flux:label for="distanceM">Metres</flux:label>
                                <flux:input id="distanceM" wire:model="distanceM" wire:change="valueChanged" type="number" min="0" max="999" placeholder="0" />
                            </flux:field>
                        </div>
                        <p class="mt-2 font-mono text-[11px] text-dim">e.g. 2 km + 400 m = 2.4 km &nbsp;|&nbsp; half marathon = 21 km + 97 m</p>
                    </div>

                    <div>
                        <p class="mb-3 font-mono text-xs text-glow-teal" id="time-group-label">// time</p>
                        <div class="grid grid-cols-3 gap-4" role="group" aria-labelledby="time-group-label">
                            <flux:field>
                                <flux:label for="timeHours">Hours</flux:label>
                                <flux:input id="timeHours" wire:model="timeHours" wire:change="valueChanged" type="number" min="0" placeholder="0" />
                            </flux:field>
                            <flux:field>
                                <flux:label for="timeMinutes">Minutes</flux:label>
                                <flux:input id="timeMinutes" wire:model="timeMinutes" wire:change="valueChanged" type="number" min="0" max="59" placeholder="0" />
                            </flux:field>
                            <flux:field>
                                <flux:label for="timeSecs">Seconds</flux:label>
                                <flux:input id="timeSecs" wire:model="timeSecs" wire:change="valueChanged" type="number" min="0" max="59" placeholder="0" />
                            </flux:field>
                        </div>
                        <p class="mt-2 font-mono text-[11px] text-dim">e.g. 1 hr + 35 min + 30 sec</p>
                    </div>

                    <flux:field>
                        <flux:label for="paceUnit">Show pace per</flux:label>
                        <flux:select id="paceUnit" wire:model="paceUnit" wire:change="valueChanged">
                            <flux:select.option value="km">Kilometre (km)</flux:select.option>
                            <flux:select.option value="miles">Mile</flux:select.option>
                        </flux:select>
                    </flux:field>

                    <button type="button" wire:click="calculate()" class="aurora-button w-full">
                        <span wire:loading.remove wire:target="calculate">Calculate My Pace</span>
                        <span wire:loading wire:target="calculate">Calculating…</span>
                    </button>
                </fieldset>
            </div>

            {{-- The result is the point of the page, so it gets the most light. --}}
            <div aria-live="polite" aria-atomic="true" class="lg:sticky lg:top-24" data-reveal style="--i: 1">
                <div @class([
                    'relative overflow-hidden rounded-2xl border p-8 text-center transition-colors duration-700',
                    'border-glow-teal/30 bg-glow-teal/5 shadow-[0_30px_80px_-40px_rgb(63_230_192/0.6)]' => $requiredPace != '',
                    'border-edge bg-panel' => $requiredPace == '',
                ])>
                    <p class="font-mono text-xs text-mute">{{ $requiredPace != '' ? '// your required pace' : '// awaiting input' }}</p>
                    @if ($requiredPace != '')
                        <p class="aurora-gradient-text mt-5 text-5xl font-medium tracking-[-0.04em] sm:text-6xl">{{ $requiredPace }}</p>
                        <p class="mt-4 text-sm text-mute">Maintain this pace consistently to hit your target time.</p>
                    @else
                        <p class="mt-5 font-mono text-5xl text-dim/60 sm:text-6xl">--:--</p>
                        <p class="mt-4 text-sm text-dim">Your pace appears here once you calculate.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

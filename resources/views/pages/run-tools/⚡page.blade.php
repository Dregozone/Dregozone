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
    <!-- Hero Section -->
    <section class="bg-stone-50 border-b border-stone-100">
        <div class="max-w-6xl mx-auto px-6 py-16 lg:py-20">
            <div class="text-center">
                <div class="flex items-center justify-center gap-3 mb-5">
                    <span class="inline-block w-8 h-0.5 bg-amber-400" aria-hidden="true"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-600">Run Tools</span>
                    <span class="inline-block w-8 h-0.5 bg-amber-400" aria-hidden="true"></span>
                </div>
                <h1 class="text-4xl font-black text-stone-900 tracking-tight mb-4">Pace Calculator</h1>
                <p class="text-lg text-stone-600 max-w-2xl mx-auto">
                    Enter your target distance and finish time to instantly calculate the running pace you'll need to maintain — in kilometres or miles.
                </p>
            </div>
        </div>
    </section>

    <!-- Calculator Section -->
    <section class="bg-white" id="main-content">
        <div class="max-w-2xl mx-auto px-6 py-16">

            <div class="bg-stone-50 border border-stone-100 rounded-2xl p-8 shadow-sm">
                <fieldset class="space-y-6">
                    <legend class="sr-only">Pace calculator inputs</legend>

                    <!-- Distance -->
                    <div>
                        <p class="text-sm font-medium text-stone-700 mb-2" id="distance-group-label">Distance</p>
                        <div class="grid grid-cols-2 gap-4" role="group" aria-labelledby="distance-group-label">
                            <flux:field>
                                <flux:label for="distanceKm">Kilometres</flux:label>
                                <flux:input
                                    id="distanceKm"
                                    wire:model="distanceKm"
                                    wire:change="valueChanged"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                />
                            </flux:field>
                            <flux:field>
                                <flux:label for="distanceM">Metres</flux:label>
                                <flux:input
                                    id="distanceM"
                                    wire:model="distanceM"
                                    wire:change="valueChanged"
                                    type="number"
                                    min="0"
                                    max="999"
                                    placeholder="0"
                                />
                            </flux:field>
                        </div>
                        <p class="mt-1.5 text-xs text-stone-400">e.g. 2 km + 400 m = 2.4 km &nbsp;|&nbsp; half marathon = 21 km + 97 m</p>
                    </div>

                    <!-- Time -->
                    <div>
                        <p class="text-sm font-medium text-stone-700 mb-2" id="time-group-label">Time</p>
                        <div class="grid grid-cols-3 gap-4" role="group" aria-labelledby="time-group-label">
                            <flux:field>
                                <flux:label for="timeHours">Hours</flux:label>
                                <flux:input
                                    id="timeHours"
                                    wire:model="timeHours"
                                    wire:change="valueChanged"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                />
                            </flux:field>
                            <flux:field>
                                <flux:label for="timeMinutes">Minutes</flux:label>
                                <flux:input
                                    id="timeMinutes"
                                    wire:model="timeMinutes"
                                    wire:change="valueChanged"
                                    type="number"
                                    min="0"
                                    max="59"
                                    placeholder="0"
                                />
                            </flux:field>
                            <flux:field>
                                <flux:label for="timeSecs">Seconds</flux:label>
                                <flux:input
                                    id="timeSecs"
                                    wire:model="timeSecs"
                                    wire:change="valueChanged"
                                    type="number"
                                    min="0"
                                    max="59"
                                    placeholder="0"
                                />
                            </flux:field>
                        </div>
                        <p class="mt-1.5 text-xs text-stone-400">e.g. 1 hr + 35 min + 30 sec</p>
                    </div>

                    <flux:field>
                        <flux:label for="paceUnit">Show pace per</flux:label>
                        <flux:select id="paceUnit" wire:model="paceUnit" wire:change="valueChanged">
                            <flux:select.option value="km">Kilometre (km)</flux:select.option>
                            <flux:select.option value="miles">Mile</flux:select.option>
                        </flux:select>
                    </flux:field>

                    <flux:button wire:click="calculate()" variant="primary" icon="bolt" class="w-full">
                        Calculate My Pace
                    </flux:button>
                </fieldset>
            </div>

            <!-- Result -->
            <div aria-live="polite" aria-atomic="true" class="mt-8">
                @if ($requiredPace != '')
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-8 text-center">
                        <p class="text-xs font-bold uppercase tracking-widest text-amber-600 mb-3">Your Required Pace</p>
                        <p class="text-5xl sm:text-6xl font-black text-stone-900 tracking-tight">{{ $requiredPace }}</p>
                        <p class="mt-3 text-sm text-stone-500">Maintain this pace consistently to hit your target time.</p>
                    </div>
                @endif
            </div>

        </div>
    </section>
</div>

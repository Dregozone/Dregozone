<?php

use Dregozone\RunTools\PaceCalculator;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.main')] class extends Component
{
    public int $distanceMeters = 0;
    public int $timeSeconds = 0;
    public string $paceUnit = 'km';

    public string $requiredPace = '';

    public function calculate(): void
    {
        $this->requiredPace = PaceCalculator::calculatePace(
            distance: $this->distanceMeters,
            time: $this->timeSeconds,
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <flux:field>
                            <flux:label for="distanceMeters">Distance <span class="text-stone-400 font-normal">(metres)</span></flux:label>
                            <flux:input
                                id="distanceMeters"
                                wire:model="distanceMeters"
                                wire:change="valueChanged"
                                type="number"
                                min="1"
                                placeholder="e.g. 5000"
                                aria-describedby="distance-hint"
                            />
                            <flux:description id="distance-hint">5 km = 5000 m, half marathon = 21 097 m</flux:description>
                        </flux:field>

                        <flux:field>
                            <flux:label for="timeSeconds">Time <span class="text-stone-400 font-normal">(seconds)</span></flux:label>
                            <flux:input
                                id="timeSeconds"
                                wire:model="timeSeconds"
                                wire:change="valueChanged"
                                type="number"
                                min="1"
                                placeholder="e.g. 1800"
                                aria-describedby="time-hint"
                            />
                            <flux:description id="time-hint">30 min = 1800 s, 1 hour = 3600 s</flux:description>
                        </flux:field>
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

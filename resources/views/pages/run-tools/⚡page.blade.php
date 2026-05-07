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

<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold mb-4">Run tools</h1>
    <p class="text-lg text-gray-600 mb-8">Here you can find various tools to help you run your projects more efficiently.</p>

    <div>
        <flux:heading>Run Tools</flux:heading>

        <flux:input wire:model="distanceMeters" wire:change="valueChanged" type="number" label="Distance (meters)" />
        <flux:input wire:model="timeSeconds" wire:change="valueChanged" type="number" label="Time (seconds)" />
        
        <flux:select wire:model="paceUnit" wire:change="valueChanged" label="Pace unit">
            <flux:select.option value="km">km</flux:select.option>
            <flux:select.option value="miles">miles</flux:select.option>
        </flux:select>

        <flux:button wire:click="calculate()" class="mt-4">
            Calculate Pace
        </flux:button>

        <flux:text class="mt-6 text-center text-3xl">
            @if ($requiredPace != '')
                Required Pace <span class="font-bold">{{ $requiredPace }}</span> seconds per km
            @endif
        </flux:text>
    </div>
</div>

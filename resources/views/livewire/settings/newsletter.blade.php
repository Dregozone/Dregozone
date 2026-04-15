<section class="w-full md:max-w-3xl mx-auto pb-10">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Newsletter')" :subheading="__('Manage your newsletter subscription')">
        <div class="my-6 w-full space-y-6">
            <flux:radio.group 
                wire:model="subscriptionStatus" 
                wire:change="updateSubscriptionStatus"
                label="Subscription status"
                variant="cards" 
                class="max-sm:flex-col"
            >
                <flux:radio value="subscribed" label="Subscribed" description="Receive occasional blog updates and newsletters." checked />
                <flux:radio value="unsubscribed" label="Unsubscribed" description="No newsletter emails will be sent to you." />
            </flux:radio.group>

            @if ($saved)
                <flux:text class="font-medium !text-green-600 dark:!text-green-400">
                    {{ __('Your subscription preference has been saved.') }}
                </flux:text>
            @endif
        </div>
    </x-settings.layout>
</section>

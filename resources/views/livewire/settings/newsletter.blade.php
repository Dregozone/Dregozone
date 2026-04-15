<section class="w-full md:max-w-3xl mx-auto pb-10">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Newsletter')" :subheading="__('Manage your newsletter subscription')">
        <div class="my-6 w-full space-y-6">
            <flux:radio.group
                wire:model.live="subscriptionStatus"
                variant="cards"
                :label="__('Subscription status')"
            >
                <flux:radio
                    value="subscribed"
                    :label="__('Subscribed')"
                    :description="__('Receive occasional blog updates and newsletters.')"
                />
                <flux:radio
                    value="unsubscribed"
                    :label="__('Unsubscribed')"
                    :description="__('No newsletter emails will be sent to you.')"
                />
            </flux:radio.group>

            @if ($saved)
                <flux:text class="font-medium !text-green-600 dark:!text-green-400">
                    {{ __('Your subscription preference has been saved.') }}
                </flux:text>
            @endif
        </div>
    </x-settings.layout>
</section>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('wfum::wfum.profile_page_title') }}</flux:heading>

    <x-settings.layout :heading="__('wfum::wfum.profile_page_title')" :subheading="__('wfum::wfum.profile_page_subtitle')">
        <form wire:submit="updatePlatformAdministration" class="my-6 w-full space-y-6">
            <flux:checkbox
                wire:model="shadow_opt_out"
                label="{{ __('wfum::wfum.shadow_opt_out_label') }}"
                description="{{ __('wfum::wfum.shadow_opt_out_description') }}"
            />

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </x-settings.layout>
</section>


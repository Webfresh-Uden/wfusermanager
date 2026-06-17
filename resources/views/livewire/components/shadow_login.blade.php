<div>
@if( $originalUser !== null )
    <flux:callout icon="clock" class="border-radius-0">
        <flux:callout.heading>{{ __('Logged in as') }} {{ $currentUser->name }} ({{ $currentUser->email }})</flux:callout.heading>
        <flux:callout.text>{{ __('Want to leave this environment?') }}

            <flux:callout.link wire:click.self="$dispatch('shadowlogout')">{{ __('Login as') }} {{ $originalUser->name }} ({{ $originalUser->email }})</flux:callout.link>
        </flux:callout.text>
    </flux:callout>
@endif
</div>

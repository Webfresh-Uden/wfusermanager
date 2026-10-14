<div>
    @if( auth()->user()->can('Switch teams') )
    <flux:select wire:change.self="updateTeamId" wire:model="team_id" placeholder="{{ __('Select team') }}" class="w-full">
            <flux:select.option value="0" wire:key="0">{{ __('Global') }}</flux:select.option>
        @foreach($teams as $team)
            <flux:select.option value="{{ $team->id }}" wire:key="{{ $team->id }}">{{ $team->name }}</flux:select.option>
        @endforeach
    </flux:select>
    @endif
</div>

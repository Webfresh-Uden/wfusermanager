<div>
    <flux:select wire:change.self="updateTeamId" wire:model="team_id" placeholder="Choose team">
            <flux:select.option value="0" wire:key="0">Global</flux:select.option>
        @foreach($teams as $team)
            <flux:select.option value="{{ $team->id }}" wire:key="{{ $team->id }}">{{ $team->name }}</flux:select.option>
        @endforeach
    </flux:select>
</div>

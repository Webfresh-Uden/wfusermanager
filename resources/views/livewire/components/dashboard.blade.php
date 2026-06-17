<div class="grid auto-rows-min gap-4 md:grid-cols-3">
    <div class="p-4 relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <flux:heading size="lg" class="mb-2">{{ __('User statistics') }}</flux:heading>
        <flux:table>
            <flux:table.rows>
                <flux:table.row>
                    <flux:table.cell><strong>{{ __('Total users') }}</strong></flux:table.cell>
                    <flux:table.cell>{{ $userCount }}</flux:table.cell>
                </flux:table.row>
                <flux:table.row>
                    <flux:table.cell><strong>{{ __('Active users') }}</strong></flux:table.cell>
                    <flux:table.cell>{{ $userActiveCount }}</flux:table.cell>
                </flux:table.row>
                <flux:table.row>
                    <flux:table.cell><strong>{{ __('Blocked users') }}</strong></flux:table.cell>
                    <flux:table.cell>{{ $userBlockedCount }}</flux:table.cell>
                </flux:table.row>
            </flux:table.rows>
        </flux:table>
        {{ App::currentLocale() }}
    </div>
    <div class="p-4 relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <flux:heading size="lg" class="mb-2">{{ __('Permission statistics') }}</flux:heading>
        <flux:table>
            <flux:table.rows>
                <flux:table.row>
                    <flux:table.cell><strong>{{ __('Teams') }}</strong></flux:table.cell>
                    <flux:table.cell>{{ $teamCount }}</flux:table.cell>
                </flux:table.row>
                <flux:table.row>
                    <flux:table.cell><strong>{{ __('Roles') }}</strong></flux:table.cell>
                    <flux:table.cell>{{ $roleCount }}</flux:table.cell>
                </flux:table.row>
                <flux:table.row>
                    <flux:table.cell><strong>{{ __('Permissions') }}</strong></flux:table.cell>
                    <flux:table.cell>{{ $permissionCount }}</flux:table.cell>
                </flux:table.row>
            </flux:table.rows>
        </flux:table>
    </div>
    <div class="p-4 relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <flux:heading size="lg" class="mb-2">{{ __('Dependencies') }}</flux:heading>
        <flux:table>
            <flux:table.rows>
                <flux:table.row>
                    <flux:table.cell><strong><flux:link href="https://laravel.com/docs/13.x/sanctum">Laravel Sanctum</flux:link></strong></flux:table.cell>
                    <flux:table.cell class="text-end">^4.3</flux:table.cell>
                </flux:table.row>
                <flux:table.row>
                    <flux:table.cell><flux:link href="https://spatie.be/docs/laravel-permission/v7/introduction">Spatie Permissions</flux:link></flux:table.cell>
                    <flux:table.cell class="text-end">^7.3</flux:table.cell>
                </flux:table.row>
            </flux:table.rows>
        </flux:table>
        <flux:heading size="lg" class="mt-4 mb-2">{{ __('Credits') }}</flux:heading>
        <flux:text>
            <strong>User manager</strong> by <strong>Roel van Lierop-Megens</strong><br/>
            {{ __('Commissioned by') }} <strong>Webfresh B.V.</strong><br/>
        </flux:text>
        <flux:table>
            <flux:table.rows>
                <flux:table.row>
                    <flux:table.cell><strong>{{ __('Current version') }}</strong></flux:table.cell>
                    <flux:table.cell class="text-end">DEV</flux:table.cell>
                </flux:table.row>
            </flux:table.rows>
        </flux:table>
    </div>
</div>

<?php

namespace WebFresh\UserManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Team extends Model
{
    use LogsActivity;

    protected $table = 'wfum_teams';

    protected $fillable = [
        'name',
        'team_id',
    ];

    public function roles(): HasMany
    {
        return $this->hasMany(WfumRole::class)->orderBy('name');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}

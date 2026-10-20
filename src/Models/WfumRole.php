<?php

namespace WebFresh\UserManager\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;

class WfumRole extends Role
{
    use LogsActivity;

    public $fillable = [
        'name',
        'team_id',
        'guard_name' => 'web',
    ];

    protected $attributes = [
        'team_id' => null
    ];

    public function team(): HasOne
    {
        return $this->hasOne(Team::class, 'id', 'team_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->useLogName('WebFresh User Manager: Role')
            ->logAll()
            ->logExcept([
                'created_at',
                'updated_at'
            ]);
    }
}

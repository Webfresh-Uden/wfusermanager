<?php

namespace WebFresh\UserManager\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
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
}

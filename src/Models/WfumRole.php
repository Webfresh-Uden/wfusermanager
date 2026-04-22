<?php

namespace WebFresh\UserManager\Models;

use Spatie\Permission\Models\Role;

class WfumRole extends Role
{
    public $fillable = [
        'name',
        'team_id',
        'guard_name' => 'web',
    ];

    public function team()
    {
        return Team::find($this->team_id);
    }
}

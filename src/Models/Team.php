<?php

namespace WebFresh\UserManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $table = 'wfum_teams';

    protected $fillable = [
        'name',
    ];

    public function roles(): HasMany
    {
        return $this->hasMany(WfumRole::class)->orderBy('name');
    }
}

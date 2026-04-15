<?php

namespace WebFresh\UserManager\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'wfum_teams';

    protected $fillable = [
        'name',
    ];
}

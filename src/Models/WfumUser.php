<?php

namespace WebFresh\UserManager\Models;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class WfumUser extends User
{
    use HasRoles;

    protected $guard_name = 'web';

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'blocked',
        'password',
    ];

    protected $casts = [
        'blocked' => 'boolean',
    ];

    public function teams(): array
    {
        $roleTeams = DB::table('model_has_roles')->select('team_id')->where('model_type', 'App\Models\User')->where('model_id', $this->id)->pluck('team_id')->toArray();
        $permissionTeams = DB::table('model_has_permissions')->select('team_id')->where('model_type', 'App\Models\User')->where('model_id', $this->id)->pluck('team_id')->toArray();
        $teamlist = array_merge($roleTeams, $permissionTeams);

        return Team::whereIn('id', $teamlist)->pluck('name')->toArray();
    }

    public function userRoles(): array
    {
        return DB::table('model_has_roles')
            ->select('model_has_roles.role_id', 'roles.name')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_id', $this->id)
            ->where('model_type', 'App\Models\User')
            ->pluck('name')
            ->toArray();
    }
}

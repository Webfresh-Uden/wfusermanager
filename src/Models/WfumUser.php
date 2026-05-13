<?php

namespace WebFresh\UserManager\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use WebFresh\UserManager\Database\Factories\WfumUserFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory(WfumUserFactory::class)]
class WfumUser extends Authenticatable
{
    use HasRoles, HasFactory;

    protected $guard_name = 'web';

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'blocked',
        'password',
        'shadow_opt_out',
    ];

    protected $casts = [
        'blocked' => 'boolean',
        'shadow_opt_out' => 'boolean',
    ];

    protected static function newFactory()
    {
        return WfumUserFactory::new();
    }

    public function userTeams(): array
    {
        $roleTeams = DB::table('model_has_roles')->select('team_id')->where('model_type', 'App\Models\User')->where('model_id', $this->id)->pluck('team_id')->toArray();
        $permissionTeams = DB::table('model_has_permissions')->select('team_id')->where('model_type', 'App\Models\User')->where('model_id', $this->id)->pluck('team_id')->toArray();
        $teamlist = array_merge($roleTeams, $permissionTeams);

        $teamListData = Team::select('id', 'name')->whereIn('id', $teamlist)->get();
        return collect($teamListData)->toArray();
    }

    public function userRoles(): array
    {
        $roleListData = DB::table('model_has_roles')
            ->select('roles.id', 'roles.name', 'roles.team_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_id', $this->id)
            ->where('model_type', 'App\Models\User')
            ->get();

        return collect($roleListData)->toArray();
    }

    public function userPermissions($teamId = null): array
    {
        $return =  DB::table('model_has_permissions')
            ->select('model_has_permissions.permission_id', 'permissions.name')
            ->leftJoin('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
            ->where('model_has_permissions.model_id', $this->id)
            ->where('model_has_permissions.model_type', 'App\Models\User');

        if( $teamId !== null ) {
            $return->where('model_has_permissions.team_id', $teamId);
        }

        return $return->pluck('permissions.name')->toArray();
    }
}

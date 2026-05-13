<?php

namespace WebFresh\UserManager\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Permission;

class WfumPermission extends Permission
{
    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'guard_name',
        'permission_group_id',
    ];

    public function permissionGroup(): BelongsTo
    {
        return $this->belongsTo(PermissionGroup::class, 'permission_group_id');
    }
}

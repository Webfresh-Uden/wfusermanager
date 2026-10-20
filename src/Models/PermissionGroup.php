<?php

namespace WebFresh\UserManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use WebFresh\UserManager\Database\Factories\PermissionGroupFactory;

class PermissionGroup extends Model
{
    /** @use HasFactory<PermissionGroupFactory> */
    use HasFactory, LogsActivity;

    protected $table = 'permission_groups';

    protected $fillable = [
        'name',
    ];

    protected static function newFactory()
    {
        return PermissionGroupFactory::new();
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class)->orderBy('name');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->useLogName('WebFresh User Manager: Permission Group')
            ->logAll()
            ->logExcept([
                'created_at',
                'updated_at'
            ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'hierarchy_level'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role')
                    ->withPivot('branch_id')
                    ->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission')
                    ->withTimestamps();
    }

    public function hasPermission($permission): bool
    {
        if (is_string($permission)) {
            return $this->permissions->contains('name', $permission);
        }

        return $this->permissions->contains($permission);
    }

    public function givePermissionTo($permission)
    {
        return $this->permissions()->attach($permission);
    }

    public function revokePermissionTo($permission)
    {
        return $this->permissions()->detach($permission);
    }

    // Check if role is platform super admin
    public function isPlatformSuperAdmin(): bool
    {
        return $this->hierarchy_level === 1;
    }

    // Check if role is school admin
    public function isSchoolAdmin(): bool
    {
        return $this->hierarchy_level === 2;
    }

    // Check if role is staff
    public function isStaff(): bool
    {
        return $this->hierarchy_level === 3;
    }

    // Check if role is student/parent
    public function isStudentParent(): bool
    {
        return $this->hierarchy_level === 4;
    }
}

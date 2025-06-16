<?php

namespace App;

use App\Models\BloodGroup;
use App\Models\Branch;
use App\Models\Lga;
use App\Models\Nationality;
use App\Models\Permission;
use App\Models\Role;
use App\Models\StaffRecord;
use App\Models\State;
use App\Models\StudentRecord;
use App\Traits\HasRolePermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRolePermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'phone', 'phone2', 'dob', 'gender', 'photo', 'address', 'bg_id', 'password', 'nal_id', 'state_id', 'lga_id', 'code', 'user_type', 'branch_id', 'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function student_record()
    {
        return $this->hasOne(StudentRecord::class);
    }

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nal_id');
    }

    public function blood_group()
    {
        return $this->belongsTo(BloodGroup::class, 'bg_id');
    }

    public function staff()
    {
        return $this->hasMany(StaffRecord::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role')
                    ->withPivot('branch_id')
                    ->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permission')
                    ->withTimestamps();
    }

    // Check if user has role
    public function hasRole($role, $branchId = null): bool
    {
        if (is_string($role)) {
            $query = $this->roles()->where('name', $role);
        } else {
            $query = $this->roles()->where('id', $role);
        }

        if ($branchId) {
            $query->wherePivot('branch_id', $branchId);
        }

        return $query->exists();
    }

    // Check if user has permission
    public function hasPermission($permission, $branchId = null): bool
    {
        // Check direct permissions
        if (is_string($permission)) {
            $hasDirectPermission = $this->permissions()->where('name', $permission)->exists();
        } else {
            $hasDirectPermission = $this->permissions()->where('id', $permission)->exists();
        }

        if ($hasDirectPermission) {
            return true;
        }

        // Check permissions through roles
        $roles = $branchId ? 
            $this->roles()->wherePivot('branch_id', $branchId)->get() : 
            $this->roles;

        foreach ($roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    // Assign role to user
    public function assignRole($role, $branchId = null)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        return $this->roles()->attach($role->id, ['branch_id' => $branchId]);
    }

    // Remove role from user
    public function removeRole($role, $branchId = null)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        $query = $this->roles()->where('role_id', $role->id);
        
        if ($branchId) {
            $query->wherePivot('branch_id', $branchId);
        }

        return $query->detach();
    }

    // Check if user is platform super admin
    public function isPlatformSuperAdmin(): bool
    {
        return $this->hasRole('platform_super_admin');
    }

    // Check if user is school admin for a branch
    public function isSchoolAdmin($branchId = null): bool
    {
        return $this->hasRole('school_admin', $branchId ?: $this->branch_id);
    }

    // Check if user is staff for a branch
    public function isStaff($branchId = null): bool
    {
        $staffRoles = ['teacher', 'librarian', 'accountant'];
        
        foreach ($staffRoles as $role) {
            if ($this->hasRole($role, $branchId ?: $this->branch_id)) {
                return true;
            }
        }
        
        return false;
    }

    // Get accessible branches for user
    public function getAccessibleBranches()
    {
        if ($this->isPlatformSuperAdmin()) {
            return Branch::where('is_active', true)->get();
        }
        
        return Branch::where('id', $this->branch_id)->where('is_active', true)->get();
    }

    // Check if user can access a specific branch
    public function canAccessBranch($branchId)
    {
        if ($this->isPlatformSuperAdmin()) {
            return true;
        }
        
        return $this->branch_id == $branchId;
    }

    // Check if user is student or parent
    public function isStudentOrParent(): bool
    {
        return $this->hasRole('student') || $this->hasRole('parent');
    }

    // Get user's highest role hierarchy level
    public function getHighestRoleLevel(): int
    {
        return $this->roles->min('hierarchy_level') ?: 5;
    }
}

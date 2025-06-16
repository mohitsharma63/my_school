<?php

namespace App\Traits;

trait HasRolePermissions
{
    /**
     * Check if user can access branch data
     */
    public function canAccessBranch($branchId): bool
    {
        // Platform Super Admin can access all branches
        if ($this->isPlatformSuperAdmin()) {
            return true;
        }

        // Users can only access their assigned branch
        return $this->branch_id == $branchId;
    }

    /**
     * Check if user can manage users
     */
    public function canManageUsers($branchId = null): bool
    {
        if ($this->isPlatformSuperAdmin()) {
            return true;
        }

        if ($this->isSchoolAdmin($branchId ?: $this->branch_id)) {
            return $this->canAccessBranch($branchId ?: $this->branch_id);
        }

        return false;
    }

    /**
     * Check if user can view/manage students
     */
    public function canManageStudents($branchId = null): bool
    {
        $targetBranch = $branchId ?: $this->branch_id;

        if (!$this->canAccessBranch($targetBranch)) {
            return false;
        }

        return $this->hasPermission('manage_students', $targetBranch) ||
               $this->hasPermission('view_students', $targetBranch);
    }

    /**
     * Check if user can manage academic data (classes, subjects, etc.)
     */
    public function canManageAcademics($branchId = null): bool
    {
        $targetBranch = $branchId ?: $this->branch_id;

        if (!$this->canAccessBranch($targetBranch)) {
            return false;
        }

        return $this->hasPermission('manage_classes', $targetBranch) ||
               $this->hasPermission('manage_subjects', $targetBranch);
    }

    /**
     * Check if user can manage financial data
     */
    public function canManageFinance($branchId = null): bool
    {
        $targetBranch = $branchId ?: $this->branch_id;

        if (!$this->canAccessBranch($targetBranch)) {
            return false;
        }

        return $this->hasPermission('manage_payments', $targetBranch);
    }

    /**
     * Get accessible branches for user
     */
    public function getAccessibleBranches()
    {
        if ($this->isPlatformSuperAdmin()) {
            return \App\Models\Branch::all();
        }

        return \App\Models\Branch::where('id', $this->branch_id)->get();
    }

    /**
     * Scope query to user's accessible branches
     */
    public function scopeAccessibleData($query, $branchColumn = 'branch_id')
    {
        if ($this->isPlatformSuperAdmin()) {
            return $query;
        }

        return $query->where($branchColumn, $this->branch_id);
    }
}

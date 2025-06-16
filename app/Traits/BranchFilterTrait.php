<?php

namespace App\Traits;

use App\Models\Branch;
use Illuminate\Support\Facades\Auth;

trait BranchFilterTrait
{
    protected function getCurrentBranchId()
    {
        // Get branch ID from session or user preference
        // For now, return the first branch or implement your logic
        return session('current_branch_id', Branch::first()->id ?? 1);
    }

    protected function applyBranchFilter($query)
    {
        return $query->where('branch_id', $this->getCurrentBranchId());
    }

    protected function setBranchForCreate($data)
    {
        $data['branch_id'] = $this->getCurrentBranchId();
        return $data;
    }

    protected function getAllBranches()
    {
        return Branch::where('is_active', true)->get();
    }

    protected function getCurrentBranch()
    {
        return Branch::findOrFail($this->getCurrentBranchId());
    }

    protected function canAccessBranch($branchId)
    {
        $user = auth()->user();

        // Super admins can access all branches
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Other users can only access their assigned branch
        return $user->branch_id == $branchId;
    }

    protected function filterByUserBranch($query)
    {
        $user = auth()->user();

        if (!$user->hasRole('super_admin')) {
            return $query->where('branch_id', $user->branch_id);
        }

        return $query;
    }

    protected function getBranchSetting($type, $default = null)
    {
        return Setting::getBranchSetting($this->getCurrentBranchId(), $type, $default);
    }
}

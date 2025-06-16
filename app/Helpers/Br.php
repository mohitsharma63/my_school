<?php

namespace App\Helpers;

use App\Models\Branch;

class Br
{
    public static function getCurrentBranch()
    {
        $branchId = session('current_branch_id');
        return Branch::find($branchId);
    }

    public static function getCurrentBranchId()
    {
        return session('current_branch_id', 1);
    }

    public static function switchBranch($branchId)
    {
        session(['current_branch_id' => $branchId]);
        return true;
    }

    public static function getActiveBranches()
    {
        return Branch::where('is_active', true)->get();
    }

    public static function applyBranchFilter($query, $branchId = null)
    {
        $branchId = $branchId ?: self::getCurrentBranchId();
        return $query->where('branch_id', $branchId);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchSwitchController extends Controller
{
    public function switch(Request $request)
    {
        $branchId = $request->input('branch_id');

        if (!auth()->user()->canAccessBranch($branchId)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        session(['current_branch_id' => $branchId]);

        return response()->json([
            'success' => true,
            'message' => 'Branch switched successfully'
        ]);
    }

    public function getCurrentBranch()
    {
        $branchId = session('current_branch_id');
        $branch = Branch::find($branchId);

        return response()->json([
            'current_branch' => $branch,
            'available_branches' => auth()->user()->getAccessibleBranches()
        ]);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use Closure;
use Illuminate\Http\Request;

class BranchMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // If no branch is selected in session, set the first active branch
        if (!session('current_branch_id')) {
            $firstBranch = Branch::where('is_active', true)->first();
            if ($firstBranch) {
                session(['current_branch_id' => $firstBranch->id]);
            }
        }

        return $next($request);
    }
}

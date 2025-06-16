<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BranchAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $branchId = $request->route('branch_id') ?: $request->input('branch_id');

        if ($branchId && !$user->canAccessBranch($branchId)) {
            abort(403, 'Access denied to this branch');
        }

        return $next($request);
    }
}

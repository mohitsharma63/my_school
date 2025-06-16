<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Get branch from request or user's default branch
        $branchId = $request->route('branch_id') ?: $user->branch_id;

        if (!$user->hasPermission($permission, $branchId)) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}

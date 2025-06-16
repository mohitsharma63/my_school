<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
use Illuminate\Support\Facades\View;

class BranchThemeMiddleware
{
    public function handle($request, Closure $next)
    {
        $branchId = session('current_branch_id');

        if ($branchId) {
            $themeSettings = Setting::where('branch_id', $branchId)
                ->whereIn('type', ['primary_color', 'secondary_color', 'theme', 'logo'])
                ->get()
                ->keyBy('type');

            $theme = [
                'primary_color' => $themeSettings->get('primary_color')->description ?? '#007bff',
                'secondary_color' => $themeSettings->get('secondary_color')->description ?? '#6c757d',
                'theme' => $themeSettings->get('theme')->description ?? 'default',
                'logo' => $themeSettings->get('logo')->description ?? null
            ];

            View::share('branchTheme', $theme);
        }

        return $next($request);
    }
}
